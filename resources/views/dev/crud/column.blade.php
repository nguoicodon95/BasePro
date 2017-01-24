<select class="form-control column_tbl">
    @foreach($columns as $column)
        <option value="{{ $column }}">{{ $column }}</option>
    @endforeach
</select>