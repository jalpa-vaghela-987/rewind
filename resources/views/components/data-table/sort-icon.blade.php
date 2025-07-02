@if ($sort_by !== $field)
    <i class="text-muted fas fa-sort"></i>
@elseif ($sort_type == 'asc')
    <i class="fas fa-sort-up"></i>
@else
    <i class="fas fa-sort-down"></i>
@endif
