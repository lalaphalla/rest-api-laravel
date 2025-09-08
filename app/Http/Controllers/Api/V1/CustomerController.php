<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Resources\v1\CustomerResource;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\V1\CustomerQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Enable query logging
        DB::enableQueryLog();

        $filter = new CustomerQuery();
        $filterItems = $filter->transform($request);

        $includeInvoices = $request->query('includeInvoices');

        $customers = Customer::where($filterItems);

        if ($includeInvoices) {
            $customers = $customers->with('invoices');
        }
        $all = Customer::all();
        $all = Customer::where($filterItems);

        if ($includeInvoices) {
            $all = $all->with('invoices');
        }
        $data = $all->get();

        // Execute the query
        $paginated = $customers->paginate()->appends($request->query());
        $paginated->getCollection(); // force query execution

        // Get all queries executed
        $queries = DB::getQueryLog();

        // Calculate total time in milliseconds
        $totalTime = collect($queries)->sum('time');

        // Optional: Include queries and total time in the response for debugging
        return response()->json([
            'include' => $data,
            'queries' => $queries,
            'total_query_time_ms' => $totalTime,
            'data' => $paginated
        ]);
        // DB::enableQueryLog();
        // // return Customer::all();
        // // return new CustomerCollection(Customer::paginate());
        // $filter = new CustomerQuery();
        // $filterItems = $filter->transform($request);

        // $includeInvoices = $request->query('includeInvoices');

        // $customers = Customer::where($filterItems);

        // if ($includeInvoices) {
        //     $customers = $customers->with('invoices');
        // }
        // // dd($customers);
        // // Get all queries executed so far

        // // Dump queries (for debugging)
        // $paginated = $customers->paginate()->appends($request->query());
        // $paginated->getCollection();
        // $queries = DB::getQueryLog();
        // dd($queries);


        // return new CustomerCollection($customers->paginate()->appends($request->query()));
        // dd(count($filterItems));
        // if (count($filterItems) == 0) {
        //     return new CustomerCollection(Customer::paginate());
        // } else {
        // dump($filterItems);
        // }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $includeInvoices = request()->query('includeInvoices');
        // dd($includeInvoices);
        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing(('invoices')));
        }

        return new CustomerResource($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
