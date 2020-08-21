
<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <table id="listingTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Customer</th>
                    <th>Customer Mob.</th>
                    <th>Customer Email</th>
                    <th class="text-align-center">Message Date</th>
                    <th class="text-align-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $value)
                    <tr id="RecordID_{{$value->id}}">
                        <td>{{ $value->subject }}</td>                
                        <td>{{ $value->name }}</td>                
                        <td>{{ $value->mobile }}</td>                
                        <td>{{ $value->email }}</td>                
                        <td class="text-align-center">{{ formatedDate($value->created_at) }}</td>
                        <td class="text-align-center">
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('contactus-view', $value->id) }}" title="View"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="center">Record not found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('elements.pagination')