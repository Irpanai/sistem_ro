<?php

namespace App\Http\Controllers;

use App\Models\TopologyNode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopologyController extends Controller
{
    public function index(Request $request): View
    {
        $query = TopologyNode::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%");
            });
        }

        if ($request->filled('area') && $request->area !== 'Semua') {
            $query->where('area', $request->area);
        }

        if ($request->filled('type') && $request->type !== 'Semua') {
            $query->where('type', $request->type);
        }

        $nodes = $query->orderBy('name')->paginate(15)->withQueryString();
        $allNodes = TopologyNode::all();
        $kecamatanList = [
            'Banjarmasin Utara',
            'Banjarmasin Timur',
            'Banjarmasin Barat',
            'Banjarmasin Selatan',
            'Banjarmasin Tengah',
        ];

        return view('topologi.index', compact('nodes', 'allNodes', 'kecamatanList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:topology_nodes,name',
            'type' => 'required|in:OLT,ODP',
            'area' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:100',
            'total_ports' => 'required|integer|min:1|max:128',
            'status' => 'required|in:Online,Offline',
        ]);

        $count = TopologyNode::count() + 1;
        $validated['node_id'] = 'NODE-'.str_pad((string) $count, 2, '0', STR_PAD_LEFT);
        $validated['used_ports'] = 0;
        $validated['connected_users'] = 0;

        TopologyNode::create($validated);

        return redirect()->route('topologi.index')
            ->with('success', "Node {$validated['name']} ({$validated['type']}) berhasil didaftarkan ke topologi.");
    }

    public function toggleStatus(TopologyNode $topologyNode): RedirectResponse
    {
        $nextStatus = $topologyNode->status === 'Online' ? 'Offline' : 'Online';
        $topologyNode->update([
            'status' => $nextStatus,
            'issue' => $nextStatus === 'Offline' ? 'Kabel Putus / Gangguan Sinyal' : null,
        ]);

        return back()->with('info', "Status {$topologyNode->name} berhasil diubah menjadi {$nextStatus}.");
    }

    public function destroy(TopologyNode $topologyNode): RedirectResponse
    {
        $name = $topologyNode->name;
        $topologyNode->delete();

        return redirect()->route('topologi.index')
            ->with('warning', "Node {$name} telah dihapus dari topologi.");
    }

    public function printDocument(TopologyNode $topologyNode): View
    {
        return view('topologi.print', ['node' => $topologyNode]);
    }
}
