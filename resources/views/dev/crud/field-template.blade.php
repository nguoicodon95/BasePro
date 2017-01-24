<td style="vertical-align: middle">
    <input type="text" style="width: 100%" required class="form-control txtFieldName"/>
</td>
<td style="vertical-align: middle">
    <select class="form-control txtdbType" style="width: 100%" required>
        <option value="string">String</option>
        <option value="char">Char</option>
        <option value="varchar">Varchar</option>
        <option value="date">Date</option>
        <option value="datetime">Datetime</option>
        <option value="time">Time</option>
        <option value="timestamp">Timestamp</option>
        <option value="text">Text</option>
        <option value="mediumtext">Mediumtext</option>
        <option value="longtext">Longtext</option>
        <option value="json">Json</option>
        <option value="jsonb">Jsonb</option>
        <option value="binary">Binary</option>
        <option value="integer">Integer</option>
        <option value="bigint">Bigint</option>
        <option value="mediumint">Mediumint</option>
        <option value="tinyint">Tinyint</option>
        <option value="smallint">Smallint</option>
        <option value="boolean">Boolean</option>
        <option value="decimal">Decimal</option>
        <option value="float">Float</option>
        <option value="double">Double</option>
        <option value="enum">Enum</option>
    </select>
</td>
<td style="vertical-align: middle">
    <input type="text" class="form-control txtValidation" placeholder="required;min:number;max:number;regex:partern"/>
</td>
<td style="vertical-align: middle">
    <select class="form-control drdHtmlType" style="width: 100%">
        <option value="text">Text</option>
        <option value="email">Email</option>
        <option value="number">Number</option>
        <option value="date">Date</option>
        <option value="file">File</option>
        <option value="password">Password</option>
        <option value="select">Select</option>
        <option value="radio">Radio</option>
        <option value="checkbox">Checkbox</option>
        <option value="textarea">TextArea</option>
    </select>
    <input type="text" class="form-control htmlValue txtHtmlValue" style="display: none"
           placeholder=""/>
</td>


<td style="vertical-align: middle">
    <select class="form-control relationship_tbl" style="display: inline-block;">
        <option value="">Liên kết bảng</option>
        @foreach($tables as $table)
            <option value="{{ $table }}">{{ $table }}</option>
        @endforeach
    </select>
    <div id="rs_column" style="display: inline-block;"></div>
</td>
