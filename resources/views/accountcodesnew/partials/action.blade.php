<a href="{{ route('accountcodesnewcreate', [$row->account_codes_hdr_id, 0]) }}" class="btn btn-sm btn-info me-1" title="Edit">
    <i class="bi bi-pencil"></i>
</a>

<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $row->account_codes_hdr_id }}" title="Delete">
    <i class="bi bi-trash"></i>
</button>