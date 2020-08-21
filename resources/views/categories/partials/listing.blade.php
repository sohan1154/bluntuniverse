
<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <table id="listingTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <!-- <th>Image</th> -->
                    <th>Title</th>
                    <th>Slug</th>
                    <th class="text-align-center">Status</th>
                    <th class="text-align-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $count=1 ?>
                @forelse($results as $value)
                    <tr id="RecordID_{{$value->id}}">
                        <td><?php echo $count++ ?>
                        <!-- <td>
                            <img class="image-box" src="{{ getImage($value->image, 'categories/'.$value->id.'/thumb') }}">
                        </td>                 -->
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->slug }}</td>
                        <td class="text-align-center">
                            <span class="changeStatus" data-href="{{ route('category-status') }}" onclick="changeStatus('{{$value->id}}');">
                                <?php if($value->status) { ?>
                                    <a href="javascript:;" class="btn btn-success btn-circle btn-sm" title="Disable"><i class="fas fa-check"></i></a>
                                <?php } else { ?> 
                                    <a href="javascript:;" class="btn btn-warning btn-circle btn-sm" title="Enable"><i class="fas fa-times"></i></a>
                                <?php } ?>
                            </span>
                        </td>
                        <td class="text-align-center">
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('category-view', $value->id) }}" title="View"><i class="fas fa-eye"></i></a>
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('category-edit', $value->id) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            <a class="btn btn-danger btn-circle btn-sm deleteRecord" href="javascript:;" data-href="{{ route('category-delete') }}" title="Delete" onclick="deleteRecord('{{$value->id}}');"><i class="fas fa-trash"></i></a>
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