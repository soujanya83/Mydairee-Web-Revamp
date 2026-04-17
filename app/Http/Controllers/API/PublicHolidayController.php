<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PubicHoliday_Model;
use Carbon\Carbon;

class PublicHolidayController extends Controller
{
	// List/filter holidays
	public function index(Request $request)
	{
		$query = PubicHoliday_Model::query();
		if ($request->filled('month')) {
			$query->where('month', (int)$request->month);
		}
		// If no month param, return all holidays
		$holidays = $query->get();
		return response()->json(['status' => true, 'holidays' => $holidays]);
	}

	// Add new holiday
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'date'     => 'required|date',
			'state'    => 'required|string|max:255',
			'occasion' => 'required|string|max:255',
			'status'   => 'nullable|in:0,1',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
		}
		$carbonDate = Carbon::parse($request->date);
		$holiday = PubicHoliday_Model::create([
			'date'      => $carbonDate->day,
			'month'     => $carbonDate->month,
			'state'     => $request->state,
			'occasion'  => $request->occasion,
			'status'    => $request->status ?? 1,
			'centerid'  => $request->user()->centerid ?? null,
			'Holiday_date' => $carbonDate
		]);
		return response()->json(['status' => 'success', 'holiday' => $holiday]);
	}

	// Update holiday
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'date'     => 'required|date',
			'occasion' => 'required|string|max:255',
			'state'    => 'required|string|max:255',
			'status'   => 'required|in:0,1',
		]);
		if ($validator->fails()) {
			return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
		}
		$carbonDate = Carbon::parse($request->date);
		$holiday = PubicHoliday_Model::findOrFail($id);
		$holiday->date      = $carbonDate->day;
		$holiday->month     = $carbonDate->month;
		$holiday->occasion  = $request->occasion;
		$holiday->state     = $request->state;
		$holiday->status    = $request->status;
		$holiday->save();
		return response()->json(['status' => 'success', 'holiday' => $holiday]);
	}

	// Delete holiday
	public function destroy($id)
	{
		$holiday = PubicHoliday_Model::findOrFail($id);
		$holiday->delete();
		return response()->json(['status' => 'success', 'message' => 'Holiday deleted successfully.']);
	}

	// Bulk delete
	public function deleteSelected(Request $request)
	{
		$ids = $request->input('ids', []);
		if (empty($ids)) {
			return response()->json([
				'status' => 'error',
				'message' => 'No holidays selected.'
			], 400);
		}

		// Find which IDs exist
		$existingIds = PubicHoliday_Model::whereIn('id', $ids)->pluck('id')->toArray();
		$notFound = array_diff($ids, $existingIds);

		// Delete only existing
		if (!empty($existingIds)) {
			PubicHoliday_Model::whereIn('id', $existingIds)->delete();
		}

		$message = 'Selected holidays deleted successfully.';
		if (!empty($notFound)) {
			$message .= ' Some IDs not found: ' . implode(', ', $notFound);
		}

		return response()->json([
			'status' => empty($existingIds) ? 'error' : 'success',
			'message' => $message,
			'not_found_ids' => array_values($notFound),
			'deleted_ids' => array_values($existingIds)
		], empty($existingIds) ? 404 : 200);
	}
}
 