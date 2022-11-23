<div class="container-fluid" style="background-color: #fff; padding: 1em;">
    <div class="container">
        <h4 style="text-align:center; color:#0090d2; font-weight: bold;">DANH SÁCH CÂU HỎI</h4>
    </div>
    {{-- <div class="container">
        <label for="subject_id" style="color:#0093ff; font-weight:bold;"></label>
        <select class="form-control" id="exam_id" name="exam_id" style="color:#0093ff;">
            @isset($exams)
                @foreach ($exams as $item)
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            @endisset
        </select>
    </div> --}}
    <br />
    <div class="asi-table-container">
        <div class="table-responsive">
            <table id="grid-data-1" class="grid-data table table-condensed table-hover table-striped">
                <thead>
                    <tr>
                        <th data-column-id="id" data-identifier="true" data-type="numeric" data-visible="false">ID</th>
                        <th data-column-id="idx" data-style="min-width:100px;">Thứ Tự</th>                       
                        <th data-column-id="content" data-style="min-width:400px;">Nội Dung</th>
                        <th data-column-id="mark" data-style="min-width:100px;">Điểm</th>
                        <th data-column-id="created_by" data-style="min-width:150px;">Người Tạo</th>
                        <th data-column-id="created_at" data-style="min-width:150px;">Ngày Tạo</th>
                        <th data-column-id="updated_by" data-style="min-width:150px;">Người Sửa</th>
                        <th data-column-id="updated_at" data-style="min-width:150px;">Ngày Sửa</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($datas)
                        @foreach ($datas as $item)
                            <tr>
                                <td>{{ $item["id"] }}</td>
                                <td>{{ $item["idx"] }}</td>
                                <td>{{ $item["content"] }}</td>
                                <td>{{ $item["mark"] }}</td>
                                <td>{{ $item["created_by"] }}</td>
                                <td>{{ $item["created_at"] }}</td>
                                <td>{{ $item["updated_by"] }}</td>
                                <td>{{ $item["updated_at"] }}</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    var rowIds = [];
    $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var grid = $("#grid-data-1").bootgrid({
                        templates: {
                            search: "",
                            actions:"",
                            footer:"",                            
                        },
                });
            
    });

</script>

<style>
    .grid-data tbody tr:hover {
        color: #3147f7 !important;
    }

    .grid-data tbody tr:nth-child(even) {
        background-color: #f2f2f2 !important;
    }

    .jconfirm {
        z-index: 99999999999;
    }

</style>
