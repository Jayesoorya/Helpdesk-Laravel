<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    private $secret = 'retro_key';

    private function decodeToken($token)
    {
        try {
            $token = str_replace('Bearer ', '', $token);
            return JWT::decode($token, new Key($this->secret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }   

    private function validateUser($decoded)
    {
        return User::where('user_id', $decoded->user_id)
                   ->where('email', $decoded->email)
                   ->first();
    }

    public function getTickets(Request $request)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded) return response()->json(['status' => false, 'message' => 'Invalid token'], 401);

        $user = $this->validateUser($decoded);
        if (!$user) return response()->json(['status' => false, 'message' => 'User validation failed'], 404);

        $tickets = Ticket::where('user_id', $user->user_id)->get();
        return response()->json(['status' => true, 'tickets' => $tickets]);
    }

    public function ticketDetails(Request $request, $id)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded) return response()->json(['status' => false, 'message' => 'Invalid token'], 401);

        $user = $this->validateUser($decoded);
        if (!$user) return response()->json(['status' => false, 'message' => 'User validation failed'], 404);

        $ticket = Ticket::find($id);
        return $ticket ?
            response()->json(['status' => true, 'ticket' => $ticket]) :
            response()->json(['status' => false, 'message' => 'Ticket not found'], 404);
    }

    public function createTicket(Request $request)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded) return response()->json(['status' => false, 'message' => 'Invalid token'], 401);

        $user = $this->validateUser($decoded);
        if (!$user) return response()->json(['status' => false, 'message' => 'User validation failed'], 401);

        $validator = Validator::make($request->all(), [
            'ticket' => 'required|min:5',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        $ticket = Ticket::create([
            'ticket' => $request->ticket,
            'description' => $request->description,
            'status' => $request->status,
            'user_id' => $user->user_id,
        ]);

        return response()->json(['status' => true, 'message' => 'Ticket created successfully'], 201);
    }

    public function updateTicket(Request $request, $id)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded) return response()->json(['status' => false, 'message' => 'Invalid token'], 401);

        $user = $this->validateUser($decoded);
        if (!$user) return response()->json(['status' => false, 'message' => 'User validation failed'], 401);

        $validator = Validator::make($request->all(), [
            'ticket' => 'required|min:5',
            'status' => 'required|in:Open,In Progress,Closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);
        }

        if ($ticket->user_id !== $user->user_id) {
            return response()->json(['status' => false, 'message' => 'You are not authorized to update this ticket'], 403);
        }
    
        $ticket->update([
            'ticket' => $request->ticket,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['status' => true, 'message' => 'Ticket updated successfully']);
    }

    public function deleteTicket(Request $request, $id)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded) return response()->json(['status' => false, 'message' => 'Invalid token'], 401);

        $user = $this->validateUser($decoded);
        if (!$user) return response()->json(['status' => false, 'message' => 'User validation failed'], 401);

        $ticket = Ticket::find($id);
        if ($ticket) {
            if ($ticket->user_id !== $user->user_id) {
                return response()->json(['status' => false, 'message' => 'You are not authorized to delete this ticket'  ], 403);
            }
            $ticket->delete();
            return response()->json(['status' => true, 'message' => 'Ticket deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);
        }
    }

    public function logout(Request $request)
    {
        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }

    public function getProfile(Request $request)
    {
        $decoded = $this->decodeToken($request->header('Authorization'));
        if (!$decoded){
             return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
        }
       // return response()->json(['decoded' => $decoded]);
        $user = User::where('user_id', $decoded->user_id)->first();
        if ($user) {
            return response()->json(['status' => true, 'user' => $user]);
        }

        return response()->json(['status' => false, 'message' => 'User not found'], 404);
    }

    public function changePassword(Request $request)
{
    $decoded = $this->decodeToken($request->header('Authorization'));
    if (!$decoded) {
        return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
    }

    $user = $this->validateUser($decoded);
    if (!$user) {
        return response()->json(['status' => false, 'message' => 'User validation failed'], 401);
    }

    $validator = Validator::make($request->all(), [
        'current_password' => 'required|min:6',
        'new_password' => 'required|min:6|confirmed', // expects new_password_confirmation field
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
    }

    if (!password_verify($request->current_password, $user->password)) {
        return response()->json(['status' => false, 'message' => 'Current password is incorrect'], 403);
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

    return response()->json(['status' => true, 'message' => 'Password changed successfully']);
}

}
