<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerViewCollection;
use App\Models\CustomerView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CustomerViewController extends Controller
{
    public function createView(string $id)
    {
        $decryptedId = Crypt::decrypt($id);
        $userId = auth()->user()->user_id;

        $view = CustomerView::where('customer_id', $decryptedId)->where('user_id', $userId)->first();

        try {

            if (!$view) {
                CustomerView::create([
                    'customer_id' => $decryptedId,
                    'user_id' => $userId
                ]);

                return response()->json([
                    'message' => 'View created successfully'
                ], 201);
            }

            $view->update([
                'updated_at' => now()
            ]);

            return response()->json([
                'message' => 'View updated successfully'
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getViews()
    {
        return response()->json([
            'views' => new CustomerViewCollection(CustomerView::latest('updated_at')->take(5)->get())
        ], 200);
    }
}
