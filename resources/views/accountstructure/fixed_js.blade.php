<script>
$(document).ready(function(){

    // Reporting group change
    $(document).on('change','.rpt_grp',function(){
        var rpt_grp = $(this).select2('val');
        var url = "{{url('getrptgrpdetails')}}?id="+rpt_grp;
        $.get(url,function(data){
            $('#rpt_type').val(data[0].rpt_type).trigger('change');
            $('#rpt_seqno').val(data[0].rpt_seq);
        });
    });

    // select2 init
    $(".select2").select2();
    $(".select2").css('width', '100%');

    // Costcenter change → load subcostcenter1
    $(document).on('change', '.costcenter_id', function(){
        var costcenter_id = $('.costcenter_id').val();
        $(".subcostcenter1_id").html('');
        $(".subcostcenter2_id").html('');
        $(".subcostcenter3_id").html('');

        if (costcenter_id != '')
        {
            var condition = "parent_class_id=" + costcenter_id;
            $(".subcostcenter1_id").jCombo("{{ URL::to('jcomboformcomp?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name') }}&order_by=sub_department_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Subcostcenter1 → load subcostcenter2
    $(document).on('change', '.subcostcenter1_id', function(){
        var subcostcenter1 = $('.subcostcenter1_id').val();
        $(".subcostcenter2_id").html('');
        $(".subcostcenter3_id").html('');

        if (subcostcenter1 != '')
        {
            var condition = "parent_class_id=" + subcostcenter1;
            $(".subcostcenter2_id").jCombo("{{ URL::to('jcomboformcomp?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name') }}&order_by=sub_department_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Subcostcenter2 → load subcostcenter3
    $(document).on('change', '.subcostcenter2_id', function(){
        var subcostcenter2 = $('.subcostcenter2_id').val();
        $(".subcostcenter3_id").html('');

        if (subcostcenter2 != '')
        {
            var condition = "parent_class_id=" + subcostcenter2;
            $(".subcostcenter3_id").jCombo("{{ URL::to('jcomboformcomp?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name') }}&order_by=sub_department_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Main Account → load Sub Account 1
    $(document).on('change', '.main_account_id', function(){
        var main_account_id = $('.main_account_id').val();

        $(".sub_account_id").html('');
        $(".future_reference1").html('');
        $(".future_reference2").html('');
        $(".sub_account4_id").html('');

        if (main_account_id != '')
        {
            var condition = "account_class_id=" + main_account_id + " and parent_class_id='0'";
            $(".sub_account_id").jCombo("{{ URL::to('jcomboform?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning') }}&order_by=account_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Sub Account 1 → load Sub Account 2
    $(document).on('change', '.sub_account_id', function(){
        var sub_account_id = $('.sub_account_id').val();

        $(".future_reference1").html('');
        $(".future_reference2").html('');
        $(".sub_account4_id").html('');

        if (sub_account_id != '')
        {
            var condition = "parent_class_id=" + sub_account_id;
            $(".future_reference1").jCombo("{{ URL::to('jcomboform?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning') }}&order_by=account_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Sub Account 2 → load Sub Account 3
    $(document).on('change', '.future_reference1', function(){
        var future_reference1 = $('.future_reference1').val();

        $(".future_reference2").html('');
        $(".sub_account4_id").html('');

        if (future_reference1 != '')
        {
            var condition = "parent_class_id=" + future_reference1;
            $(".future_reference2").jCombo("{{ URL::to('jcomboform?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning') }}&order_by=account_code asc&" + condition,
            {selected_value:""});
        }
    });

    // Sub Account 3 → load Sub Account 4
    $(document).on('change', '.future_reference2', function(){
        var future_reference2 = $('.future_reference2').val();

        $(".sub_account4_id").html('');

        if (future_reference2 != '')
        {
            var condition = "parent_class_id=" + future_reference2;
            $(".sub_account4_id").jCombo("{{ URL::to('jcomboform?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning') }}&order_by=account_code asc&" + condition,
            {selected_value:""});
        }
    });

});
</script>