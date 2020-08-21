
<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <table id="listingTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Image for</th>
                    <th>Image</th>
                    <th>Title & Image url</th>
                    <th class="text-align-center">Status</th>
                    <th class="text-align-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $count=1 ?>
                @forelse($results as $value)
                    <tr id="RecordID_{{$value->id}}">
                        <td><?php echo $count++ ?></td>
                        <td>{{ ucfirst($value->image_type) }}</td>
                        <td>
                            <img class="image-box" src="{{ getImage($value->image, 'galleries/'.$value->id.'/thumb') }}">
                        </td>                
                        <td>
                            {{ $value->title }} <br /><br />
                            <small><strong>Image url:</strong> {{ getImage($value->image, 'galleries/'.$value->id) }}</small>
                            <span class="image-path" data-image-path="{{ getImage($value->image, 'galleries/'.$value->id) }}"></span>
                        </td>
                        <td class="text-align-center">
                            <span class="changeStatus" data-href="{{ route('gallery-status') }}" onclick="changeStatus('{{$value->id}}');">
                                <?php if($value->status) { ?>
                                    <a href="javascript:;" class="btn btn-success btn-circle btn-sm" title="Disable"><i class="fas fa-check"></i></a>
                                <?php } else { ?> 
                                    <a href="javascript:;" class="btn btn-warning btn-circle btn-sm" title="Enable"><i class="fas fa-times"></i></a>
                                <?php } ?>
                            </span>
                        </td>
                        <td class="text-align-center">
                            <a class="btn btn-warning btn-sm copied" href="javascript:;" title="Copy Image url" onclick="copyClipboard('{{$value->id}}');">Copy Image url</a>
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('gallery-view', $value->id) }}" title="View"><i class="fas fa-eye"></i></a>
                            <a class="btn btn-info btn-circle btn-sm" href="{{ route('gallery-edit', $value->id) }}" title="Edit"><i class="fas fa-edit"></i></a>
                            <a class="btn btn-danger btn-circle btn-sm deleteRecord" href="javascript:;" data-href="{{ route('gallery-delete') }}" title="Delete" onclick="deleteRecord('{{$value->id}}');"><i class="fas fa-trash"></i></a>
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