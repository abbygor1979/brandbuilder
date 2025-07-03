<?php

namespace App\Http\Controllers;

use App\Models\DomainZone;
use App\Models\Niche;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function storeNiche(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Niche::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Niche added successfully!');
    }

    public function destroyNiche(Niche $niche)
    {
        $niche->delete();
        return redirect()->back()->with('success', 'Niche deleted successfully!');
    }

    public function storeDomainZone(Request $request)
    {
        $request->validate([
            'zone' => 'required|string|max:50',
            'is_popular' => 'boolean',
        ]);
        DomainZone::create($request->only('zone', 'is_popular'));
        return redirect()->back()->with('success', 'Domain zone added successfully!');
    }

    public function destroyDomainZone(DomainZone $domainZone)
    {
        $domainZone->delete();
        return redirect()->back()->with('success', 'Domain zone deleted successfully!');
    }

    public function users()
    {
        $users = User::with('subscription')->get();
        return view('admin.users', compact('users'));
    }
}