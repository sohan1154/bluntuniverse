<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
    <div class="row">
        <div class="col-sm-12 col-md-5">
            <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
                Showing 
                    @if ( $results->lastPage() == $results->currentPage() )
                        @if ( $results->total() == 0 )
                            {{ $results->total() }}
                        @else 
                            {{ (($results->perPage() * ($results->currentPage()-1)) + 1) }}
                        @endif
                    @elseif ( $results->currentPage() == 1)
                        {{ $results->currentPage() }}
                    @else
                        {{ (($results->perPage() * ($results->currentPage()-1)) + 1) }}
                    @endif
                to 
                    @if ( $results->lastPage() == $results->currentPage() )
                        {{ $results->total() }}
                    @else
                        {{ $results->perPage() * $results->currentPage() }}
                    @endif
                of
                    {{ $results->total() }}
                records
            </div>
        </div>

        <div class="col-sm-12 col-md-7">
            <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
                {{ $results->appends(request()->all())->render() }}
            </div>
        </div>
    </div>
</div>
