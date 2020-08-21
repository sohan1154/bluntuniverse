
<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <table id="listingTable" class="table table-bordered">
            <thead>
                <tr>
                <th class="text-align-center">SNo.</th>

                    <th class="text-align-center">Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th class="text-align-center">Recordings</th>
                    <th class="text-align-center">Status</th>
                    <th class="text-align-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $count=1 ?>
                @forelse($results as $value)
                    <tr id="RecordID_{{$value->id}}">
                        <td><?php echo $count++ ?>
                        <td class="text-align-center"><span class="imageTag"><img src="{{ getImage($value->image, 'users/'.$value->id.'/thumb') }}"></span></td>
                        <td>{{ $value->name }}</td>                       
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->mobile }}</td>
                        <td class="text-align-center">{{ @$value->recordings->count() }}</td>
                        <td class="text-align-center">
                            <span class="changeStatus" data-href="{{ route('user-status') }}" onclick="changeStatus('{{$value->id}}');">
                                <?php if($value->status) { ?>
                                    <a href="javascript:;" class="btn btn-success btn-sm" title="Active">Active</a>
                                <?php } else { ?> 
                                    <a href="javascript:;" class="btn btn-warning btn-sm" title="Deactive">Deactive</a>
                                <?php } ?>
                            </span>
                        </td>
                        <td class="text-align-center">
                           <?php if(!$value->is_verified) { ?>
                            <a class="btn btn-warning btn-circle btn-sm verifyRecord" href="javascript:;" data-href="{{ route('user-verify') }}" title="Verify" onclick="verifyRecord('{{$value->id}}');"><i class="fas fa-check"></i></a>
                            <?php } ?>
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('user-view', $value->id) }}" title="View"><i class="fas fa-eye"></i></a>
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('user-edit', $value->id) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            <!-- <a class="btn btn-info btn-circle btn-sm" href="{{ route('user-change-password', $value->id) }}" title="Change Password"><i class="fas fa-key"></i></a> -->
                            <a class="btn btn-danger btn-circle btn-sm deleteRecord" href="javascript:;" data-href="{{ route('user-delete') }}" title="Delete" onclick="deleteRecord('{{$value->id}}');"><i class="fas fa-trash"></i></a>
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