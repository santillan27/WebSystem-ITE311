<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;
use CodeIgniter\Controller;

class Announcement extends BaseController
{
    protected $helpers = ['form', 'url'];

    // View all announcements (accessible by all users)
    public function index()
    {
        $model = new AnnouncementModel();
        $data['announcements'] = $model->getAnnouncementsWithUser();
        $data['user_role'] = session()->get('role');

        return view('announcements/index', $data);
    }

    // Show form to create announcement (admin only)
    public function create()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/announcements')->with('error', 'Only admins can create announcements.');
        }

        return view('announcements/create');
    }

    // Store new announcement (admin only)
    public function store()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/announcements')->with('error', 'Only admins can create announcements.');
        }

        if (!$this->validate([
            'title'   => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AnnouncementModel();
        $data = [
            'user_id' => session()->get('user_id'),
            'title'   => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];

        if ($model->insert($data)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Announcement created successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create announcement.');
        }
    }

    // Show form to edit announcement (admin only)
    public function edit($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/announcements')->with('error', 'Only admins can edit announcements.');
        }

        $model = new AnnouncementModel();
        $data['announcement'] = $model->find($id);

        if (!$data['announcement']) {
            return redirect()->to('/announcements')->with('error', 'Announcement not found.');
        }

        return view('announcements/edit', $data);
    }

    // Update announcement (admin only)
    public function update($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/announcements')->with('error', 'Only admins can edit announcements.');
        }

        if (!$this->validate([
            'title'   => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new AnnouncementModel();
        $data = [
            'title'   => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
        ];

        if ($model->update($id, $data)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Announcement updated successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update announcement.');
        }
    }

    // Delete announcement (admin only)
    public function delete($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/announcements')->with('error', 'Only admins can delete announcements.');
        }

        $model = new AnnouncementModel();
        if ($model->delete($id)) {
            return redirect()->to('/admin/dashboard')->with('success', 'Announcement deleted successfully!');
        } else {
            return redirect()->to('/admin/dashboard')->with('error', 'Failed to delete announcement.');
        }
    }
}
