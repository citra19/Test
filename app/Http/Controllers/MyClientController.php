<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\my_client;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MyClientController extends Controller
{
    public function index()
    {
        $clients = my_client::all();
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:250',
            'slug' => 'required|max:100|unique:my_clients',
            'is_project' => 'required|in:0,1',
            'self_capture' => 'required|max:1',
            'client_prefix' => 'required|max:4',
            'client_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|max:50',
            'city' => 'nullable|max:50',
        ]);

        if ($request->hasFile('client_logo')) {
            $path = $request->file('client_logo')->store('clients', 's3');
            $data['client_logo'] = Storage::disk('s3')->url($path);
        }

        $client = my_client::create($data);

        return response()->json($client, 201);
    }

    public function show($slug)
    {
        $client = Redis::get("client:{$slug}");
        if (!$client) {
            $client = my_client::where('slug', $slug)->firstOrFail();
            Redis::set("client:{$slug}", json_encode($client));
        } else {
            $client = json_decode($client);
        }

        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $client = my_client::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|max:250',
            'slug' => 'required|max:100|unique:my_clients,slug,' . $id,
            'is_project' => 'required|in:0,1',
            'self_capture' => 'required|max:1',
            'client_prefix' => 'required|max:4',
            'client_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|max:50',
            'city' => 'nullable|max:50',
        ]);

        if ($request->hasFile('client_logo')) {
            $path = $request->file('client_logo')->store('clients', 's3');
            $data['client_logo'] = Storage::disk('s3')->url($path);
        }

        $client->update($data);
        Redis::del("client:{$client->slug}");
        Redis::set("client:{$data['slug']}", json_encode($client));

        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = my_client::findOrFail($id);
        $client->update(['deleted_at' => now()]);
        Redis::del("client:{$client->slug}");

        return response()->json(['message' => 'Client soft deleted'], 200);
    }
}