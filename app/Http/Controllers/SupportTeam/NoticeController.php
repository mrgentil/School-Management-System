<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notice\NoticeCreate;
use App\Http\Requests\Notice\NoticeUpdate;
use App\Models\Notice;
use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    protected $user, $my_class;

    public function __construct(UserRepo $user, MyClassRepo $my_class)
    {
        $this->middleware('teamSA', ['except' => ['index', 'show']]);
        $this->user = $user;
        $this->my_class = $my_class;
    }

    public function index()
    {
        $data['notices'] = Notice::with('creator')
            ->forAudience($this->getUserAudience())
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.support_team.notices.index', $data);
    }

    public function create()
    {
        return view('pages.support_team.notices.create');
    }

    public function store(NoticeCreate $req)
    {
        $data = $req->validated();
        $data['created_by'] = Auth::id();

        Notice::create($data);

        return redirect()->route('notices.index')->with('flash_success', __('msg.store_ok'));
    }

    public function show(Notice $notice)
    {
        return view('pages.support_team.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('pages.support_team.notices.edit', compact('notice'));
    }

    public function update(NoticeUpdate $req, Notice $notice)
    {
        $notice->update($req->validated());

        return redirect()->route('notices.index')->with('flash_success', __('msg.update_ok'));
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('notices.index')->with('flash_success', __('msg.del_ok'));
    }

    private function getUserAudience()
    {
        $userType = Auth::user()->user_type;
        
        switch ($userType) {
            case 'student':
                return 'students';
            case 'teacher':
                return 'teachers';
            case 'parent':
                return 'parents';
            default:
                return 'staff';
        }
    }
}
