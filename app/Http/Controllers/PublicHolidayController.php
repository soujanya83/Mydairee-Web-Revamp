<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PubicHoliday_Model;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PublicHolidayController extends Controller
{

    public function holidayEvents()
    {
        $holidays = PubicHoliday_Model::where('status',1)->get()->map(function ($holiday) {
            return [
                'title' => '📅 ' . $holiday->occasion,
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
            'date'     => 'required|date',
            'state'    => 'required|string|max:255',
            'occasion' => 'required|string|max:255',
            'status'   => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Split date into day & month
        $carbonDate = \Carbon\Carbon::parse($request->date);

        PubicHoliday_Model::insert([
            'date'      => $carbonDate->day,   // 01–31
            'month'    => $carbonDate->month, // 01–12
            'state'    => $request->state,
            'occasion' => $request->occasion,
            'status'   => $request->status,
        ]);

        return back()->with('success', 'Holiday Saved Successfully');
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
