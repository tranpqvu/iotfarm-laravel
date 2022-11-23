<div class="row justify-content-center">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4>Cài Đặt Bài KT Cho Lớp</h4>
            </div> -->
            <div class="card-body">
                <div class="col-xs-4 col-xs-offset-4">
                                    
                    <div class="form-group">
                        <label for="school_year_id" style="color:#0093ff; font-weight:bold;">Năm Học</label>
                        <select class="form-control" id="school_year_id" name="school_year_id" style="color:#0093ff;">
                            @isset($years)
                                @foreach ($years as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subject_id" style="color:#0093ff; font-weight:bold;">Môn Học</label>
                        <select class="form-control" id="subject_id" name="subject_id" style="color:#0093ff;">
                            @isset($subjects)
                                @foreach ($subjects as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="class_id" style="color:#0093ff; font-weight:bold;">Lớp Học</label>
                        <select class="form-control" id="class_id" name="class_id" style="color:#0093ff;">
                            @isset($class)
                                @foreach ($class as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="time_start" style="color:#0093ff; font-weight:bold;">Thời Gian Mở</label>
                        <div class='input-group date' id='time_open'>
                            <input type='text' class="form-control" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="time_start" style="color:#0093ff; font-weight:bold;">Thời Gian Đóng</label>
                        <div class='input-group date' id='time_close'>
                            <input type='text' class="form-control" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('#time_open').datetimepicker();
        $('#time_close').datetimepicker();
    });
</script>