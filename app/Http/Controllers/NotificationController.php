<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications->where('notifiable_id',loginId())->filter(function($value, $key){
            return $value->data['flag_system'] == 1;
        });

        //  dd($notifications[0]);
        return view('modules.Notification.list',
        [
            'notifications' => $notifications,
        ]);
    }


    public function ajaxGetNotification()
    {
        $user = auth()->user();

        $notifications = $user->notifications->where('notifiable_id',loginId())->filter(function($value, $key){
                                return $value->data['flag_system'] == 1;
                        })
                        ->each(function ($item, $key) {
                            if ($item->data['url']) {
                                $item->route = $item->data['url'];
                            }
                        })->toArray();

        $unreadCount = $user->unreadNotifications->filter(function($value, $key){
                            return $value->data['flag_system'] == 1;
                        })
                        ->count();

        return response()->json([
            'data' => $notifications,
            'total' => $unreadCount
        ], 201);
    }


    public function markAsRead(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = auth()->user();
            $user->unreadNotifications
                ->when($request->id, function($query) use ($request){
                    return $query->where('id', $request->id);
                })
                ->markAsRead();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'data' => "error"
            ], 404);
        }

        return response()->json([
            'data' => "success"
        ], 201);
    }

    public function destroyByRow(Request $request)
    {

        $id = $request->id;

        try {
            $user = auth()->user();

            $user->notifications->where('id', $id)->first()->delete();
            
            return redirect()->route('notification.index')->with('success', 'Notifikasi berjaya dihapus!');
    
        } catch (\Exception $e) {
            return redirect()->route('notification.index')->with('error', 'Notifikasi tidak berjaya dihapus!' . ' ' . $e->getMessage());
        }

    }
}
