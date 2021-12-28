<?php

namespace App\Http\Controllers;

use App\User;
use App\Salary;
use Carbon\Carbon;
use App\Department;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Http\Requests\StoreSalary;


use App\Http\Requests\UpdateSalary;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('view_salary')) {
            return abort(404);
        }
        return view('salaries.index');
    }


    public function ssd()
    {
        if (!auth()->user()->can('view_salary')) {
            return abort(404);
        }
        $salaries = Salary::query();
        return Datatables::of($salaries)
        ->filterColumn('employee_name', function ($query, $keyword) {
            $query->whereHas('user', function ($q1) use ($keyword) {
                $q1->where('name', 'like', '%'.$keyword.'%');
            });
        })
        ->addColumn('plus', function ($each) {
            return null;
        })
        ->editColumn('employee_name', function ($each) {
            return $each->user ? $each->user->name : '_';
        })
        ->editColumn('month', function ($each) {
            return Carbon::parse('2021-'.$each->month.'-01')->format('M');
        })
        ->editColumn('amount', function ($each) {
            return number_format($each->amount);
        })
        ->editColumn('updated_at', function ($each) {
            return Carbon::parse($each->updated_at)->format('d-m-Y H:i:s A');
        })
        ->addColumn('action', function ($each) {
            if (auth()->user()->can('edit_salary')) {
                $edit = '<a href="'.route('salaries.edit', $each->id).'"><i class="fas fa-edit text-warning"></i></a>';
            }
            if (auth()->user()->can('delete_salary')) {
                $delete = '<a href="#" data-id="'.$each->id.'" id="delete"><i class="fas fa-trash text-danger delete"></i></a>';
            }
            
            return '<div class="action-icon">'.$edit.$delete.'</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('create_salary')) {
            return abort(404);
        }

        $employees = User::orderBy('employee_id')->get();
        return view('salaries.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalary $request)
    {
        if (!auth()->user()->can('create_salary')) {
            return abort(404);
        }
        $salary =  new Salary();
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
    
        $salary->save();

        return redirect()->route('salaries.index')->with('create', 'A salary was created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('edit_salary')) {
            return abort(404);
        }
        $employees = User::Orderby('employee_id')->get();
        $salary = Salary::find($id);
        return view('salaries.edit', compact('salary', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalary $request, $id)
    {
        if (!auth()->user()->can('edit_salary')) {
            return abort(404);
        }
        $salary = Salary::findOrFail($id);

        $employees = User::Orderby('employee_id')->get();
       
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
      
        $salary->update();

        return redirect()->route('salaries.index')->with('update', 'An salary was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('delete_salary')) {
            return abort(404);
        }
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return 'success';
    }
}
