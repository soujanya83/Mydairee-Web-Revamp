<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PubicHoliday_Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PublicHolidayController extends Controller
{

    public function holidayEvents()
    {
        $holidays = PubicHoliday_Model::all()->map(function ($holiday) {
            return [
                'title' => '📅 ' . $holiday->occasion,
                'occasion' => $holiday->occasion,
                'date'  => Carbon::createFromDate(
                    now()->year,
                    $holiday->month,
                    $holiday->date
                )->toDateString(),
                'state' => $holiday->state ?? '--',
                'status' => $holiday->status ? 'Active' : 'Inactive',
            ];
        });

        return response()->json([
            'status' => true,
            'events' => $holidays
        ]);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'date'     => 'required|date',
            'occasion' => 'required|string|max:255',
            'state'    => 'required|string|max:255',
            'status'   => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $carbonDate = \Carbon\Carbon::parse($request->date);

        $holiday = PubicHoliday_Model::findOrFail($id);
        $holiday->date      = $carbonDate->day;
        $holiday->month    = $carbonDate->month;

        $holiday->occasion = $request->occasion;
        $holiday->state    = $request->state;
        $holiday->status   = $request->status;
        $holiday->save();

        return back()->with('success', 'Holiday updated successfully!');
    }


    public function holiday_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date'     => 'nullable|date',
            'state'    => 'nullable|string|max:255',
            'occasion' => 'nullable|string|max:255',
            'status'   => 'nullable|in:0,1',
            'csvExcel' => 'nullable|file|mimes:csv,txt,xls,xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ---------- Case 1: CSV/Excel Upload ----------
        if ($request->hasFile('csvExcel')) {
            $file = $request->file('csvExcel');
            $headerMap = null;
            $headerFound = false;
            $insertedCount = 0;

            // CSV Handling
            if ($file->getClientOriginalExtension() === 'csv') {
                $handle = fopen($file->getRealPath(), "r");
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $normalized = array_map('strtolower', array_map('trim', $row));

                    // Detect header row
                    if (
                        !$headerMap &&
                        (
                            in_array('date', $normalized) ||
                            in_array('day', $normalized) ||
                            in_array('holiday_date', $normalized)
                        ) &&
                        (
                            in_array('state', $normalized) ||
                            in_array('province', $normalized) ||
                            in_array('region', $normalized)
                        )
                    ) {
                        $headerMap = array_flip($normalized);
                        $headerFound = true;
                        continue;
                    }

                    if (!$headerMap) {
                        continue; // skip until header found
                    }

                    $dayRaw = $this->getColumnValue($row, $headerMap, ['day', 'date', 'holiday_date']);
                    $dayCarbon = $this->parseFlexibleDate($dayRaw);

                    $state    = $this->getColumnValue($row, $headerMap, ['state', 'province', 'region']);
                    $occasion = $this->getColumnValue($row, $headerMap, ['occasion', 'holiday', 'festival', 'event']);

                    $status = strtolower((string)($this->getColumnValue($row, $headerMap, ['status', 'active']) ?? '1'));
                    $status = ($status === 'active' || $status === '1') ? '1' : '0';

                    if ($dayCarbon && $state && $occasion) {
                        PubicHoliday_Model::insert([
                            'date'      => $dayCarbon->day,
                            'month'     => $dayCarbon->month,
                            'state'     => $state,
                            'occasion'  => $occasion,
                            'status'    => $status,
                        ]);
                        $insertedCount++;
                    }
                }
                fclose($handle);
            } else {
                // Excel Handling
                $data = Excel::toArray([], $file);

                foreach ($data[0] as $index => $row) {
                    $normalized = array_map(fn($v) => strtolower(trim($v)), $row);

                    if (
                        !$headerMap &&
                        (
                            in_array('date', $normalized) ||
                            in_array('day', $normalized) ||
                            in_array('holiday_date', $normalized)
                        ) &&
                        (
                            in_array('state', $normalized) ||
                            in_array('province', $normalized) ||
                            in_array('region', $normalized)
                        )
                    ) {
                        $headerMap = array_flip($normalized);
                        $headerFound = true;
                        continue;
                    }

                    if (!$headerMap) {
                        continue;
                    }

                    $dayRaw = $this->getColumnValue($row, $headerMap, ['day', 'date', 'holiday_date']);
                    $dayCarbon = $this->parseFlexibleDate($dayRaw);

                    $state    = $this->getColumnValue($row, $headerMap, ['state', 'province', 'region']);
                    $occasion = $this->getColumnValue($row, $headerMap, ['occasion', 'holiday', 'festival', 'event']);

                    $status = strtolower((string)($this->getColumnValue($row, $headerMap, ['status', 'active']) ?? '1'));
                    $status = ($status === 'active' || $status === '1') ? '1' : '0';

                    if ($dayCarbon && $state && $occasion) {
                        PubicHoliday_Model::insert([
                            'date'      => $dayCarbon->day,
                            'month'     => $dayCarbon->month,
                            'state'     => $state,
                            'occasion'  => $occasion,
                            'status'    => $status,
                        ]);
                        $insertedCount++;
                    }
                }
            }

            // If header never detected → return error
            if (!$headerFound) {
                // dd('here');

                return redirect()->back()->with([
                    'status' => 'error',
                    'msg'    => 'Header not found in file. Please download sample for referance',
                    "type" => "public_holiday"
                ]);
            }

            return redirect()->back()->with([
                'status' => 'success',
                'msg' => "Holidays imported successfully! ✅ {$insertedCount} entries added."
            ]);
        }

        // ---------- Case 2: Manual Form Entry ----------
        if ($request->date && $request->state && $request->occasion) {
            $carbonDate = Carbon::parse($request->date);

            PubicHoliday_Model::insert([
                'date'      => $carbonDate->day,
                'month'     => $carbonDate->month,
                'state'     => $request->state,
                'occasion'  => $request->occasion,
                'status'    => $request->status ?? 1,
            ]);

            return redirect()->back()->with([
                'status' => 'success',
                'msg' => "Holidays imported successfully! ✅ "
            ]);
        }
        return redirect()->back()->with([
            'status' => 'error',
            'msg' => "No data provided. please fill correctly "
        ]);
    }


    function getColumnValue($row, $headerMap, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            if (isset($headerMap[$name]) && isset($row[$headerMap[$name]])) {
                return $row[$headerMap[$name]];
            }
        }
        return null;
    }


    function parseFlexibleDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Handle Excel numeric dates (serial numbers)
        if (is_numeric($value) && $value > 30000) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                return null;
            }
        }

        // Try multiple formats
        $formats = [
            'Y-m-d',
            'd-m-Y',
            'd/m/Y',
            'm-d-Y',
            'm/d/Y',
            'd M Y',
            'M d, Y'
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim($value));
            } catch (\Exception $e) {
                continue;
            }
        }

        // Last fallback → Carbon parse (can guess natural language dates)
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }


    function add_public_holiday(Request $request)
    {
        $query = PubicHoliday_Model::query();

        // Filter by month
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }

        $holidayData = $query->get()->map(function ($holiday) {
            $holiday->full_date = Carbon::createFromDate(
                now()->year,
                $holiday->month,
                $holiday->date
            );
            return $holiday;
        })->sortBy(function ($holiday) {
            $currentMonth = now()->month;

            // 🎯 Pehle current month ke holidays ko priority dena
            return sprintf(
                '%02d-%02d',
                ($holiday->month < $currentMonth ? $holiday->month + 12 : $holiday->month),
                $holiday->date
            );
        })->values(); // indexes reset

        return view('holiday.add_public_holiday', compact('holidayData'));
    }



    public function changeStatus($id)
    {
        $wifi = PubicHoliday_Model::findOrFail($id);
        $wifi->status = $wifi->status == 1 ? 0 : 1; // toggle
        $wifi->save();

        return back()->with('success', 'Holiday status updated');
    }

    public function destroy($id)
    {
        $wifi = PubicHoliday_Model::findOrFail($id);
        $wifi->delete();

        return back()->with('success', 'Holiday deleted successfully');
    }
}
