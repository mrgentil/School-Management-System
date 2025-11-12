<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\MyClass;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $book;

    public function __construct(Book $book)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->book = $book;
    }

    public function index()
    {
        $d['books'] = $this->book->all();
        $d['classes'] = MyClass::all();
        return view('pages.support_team.books.index', $d);
    }

    public function store(BookRequest $req)
    {
        $data = $req->all();
        $this->book->create($data);
        return Qs::jsonStoreOk();
    }

    public function show($id)
    {
        $d['book'] = $book = $this->book->find($id);
        return !is_null($book) ? view('pages.support_team.books.show', $d) 
            : Qs::goWithDanger('books.index', __('msg.invalid_id'));
    }

    public function edit($id)
    {
        $d['book'] = $book = $this->book->find($id);
        $d['classes'] = MyClass::all();
        return !is_null($book) ? view('pages.support_team.books.edit', $d) 
            : Qs::goWithDanger('books.index', __('msg.invalid_id'));
    }

    public function update(BookRequest $req, $id)
    {
        $book = $this->book->find($id);
        if(!$book){
            return Qs::goWithDanger('books.index', __('msg.invalid_id'));
        }

        $data = $req->all();
        $book->update($data);
        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $book = $this->book->find($id);
        if(!$book){
            return back()->with('flash_danger', __('msg.invalid_id'));
        }

        $book->delete();
        return back()->with('flash_success', __('msg.del_ok'));
    }
}
