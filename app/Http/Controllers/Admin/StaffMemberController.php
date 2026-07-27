<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffMemberRequest;
use App\Http\Requests\Admin\UpdateStaffMemberRequest;
use App\Models\StaffMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffMemberController extends Controller
{
    private function roles(): array
    {
        return [
            'Site Manager',
            'Production Technicians',
            'Quality Control Officer',
            'Agronomist',
            'Maintenance Technician',
            'Sales and Marketing Officer',
            'Delivery/Logistics Personnel',
            'Administrative and Compliance Officer',
            'Security Personnel',
            'Other',
        ];
    }

    public function index(): View
    {
        return view('admin.staff-members.index');
    }

    public function create(): View
    {
        return view('admin.staff-members.create', ['roles' => $this->roles()]);
    }

    public function store(StoreStaffMemberRequest $request): RedirectResponse
    {
        StaffMember::create($request->validated());

        return redirect()->route('admin.staff-members.index')->with('success', 'Staff member created.');
    }

    public function edit(StaffMember $staffMember): View
    {
        return view('admin.staff-members.edit', [
            'staffMember' => $staffMember,
            'roles' => $this->roles(),
        ]);
    }

    public function update(UpdateStaffMemberRequest $request, StaffMember $staffMember): RedirectResponse
    {
        $staffMember->update($request->validated());

        return redirect()->route('admin.staff-members.index')->with('success', 'Staff member updated.');
    }

    public function destroy(StaffMember $staffMember): RedirectResponse
    {
        $staffMember->delete();

        return redirect()->route('admin.staff-members.index')->with('success', 'Staff member deleted.');
    }
}
