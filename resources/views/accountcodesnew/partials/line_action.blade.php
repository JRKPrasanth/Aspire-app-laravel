<button class="btn btn-sm btn-info edit-line-btn"
    data-id="{{ $id }}"
    data-line_no="{{ $line_no }}"
    data-account_code="{{ $account_code }}"
    data-account_code_meaning="{{ $account_code_meaning }}"
    data-description="{{ $description }}"
    data-active="{{ $active }}">
    <i class="bi bi-pencil"></i>
</button>

<button class="btn btn-sm btn-danger delete-line-btn" data-id="{{ $id }}">
    <i class="bi bi-trash"></i>
</button>