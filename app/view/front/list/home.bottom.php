<script>
    const base_url = '<?= base_url() ?>';
    const source = {
        source_id: '<?= $sources[0]->id ?>',
        base_url: '<?= $sources[0]->api_base_url ?>',
        api_name: '<?= $sources[0]->source ?>'
    };
    let edit_id;
    let table;
    let selectedAnime = [];
    let apiResponse = [];
    let userAnime = [];
    let status_enum = [
        "plan to watch",
        "watching",
        "completed",
        "on-hold",
        "dropped"
    ]
    let editQueue = [];


    $(document).ready(function() {
        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "api/anime_list/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columnDefs: [{
                width: '20%',
                targets: 1
            }],
            columns: [{
                    title: 'No',
                    render: function(data, type, row, meta) {
                        return parseInt(meta.row) + 1;
                    }
                },
                {
                    title: 'Image',
                    data: "image_url",
                    render: function(data, type, row, meta) {
                        return "<img style=\"width:200px; cursor: pointer\" src=\"" + data + "\" onclick=\"image_modal(this)\" data-bs-toggle=\"modal\" data-bs-target=\"#modal-image\" >";
                    }
                },
                {
                    title: 'Title',
                    data: "title"
                },
                {
                    title: 'Status',
                    data: "status",
                    render: function(data, type, row, meta) {
                        let options = '';
                        status_enum.forEach((val, idx) => {
                            options += '<option value="' + val + '" ' + (val == data ? 'selected' : '') + '>' + capitalize(val) + '</option>'
                        });
                        return `<text class="view-mode">` + data + `</text>
                             <select class="form-select edit-mode d-none" style="width:150px" id="edit-status-` + row.id + `" onchange="onEdit(` + row.id + `, 'status', this)">
                                <option value="" disabled>--Select Status--</option>
                                ` + options + `
                            </select>
                        `;
                    }
                },
                {
                    title: 'Episodes Watched',
                    data: "episodes_watched",
                    render: function(data, type, row, meta) {
                        return `<span id="edit-episodes_watched-`+row.id+`">` + data + "</span> / " + row.episodes + `
                        <div class="btn-group ms-1 edit-mode d-none" role="group">
                            <button type="button" class="btn btn-sm btn-danger" onMouseDown="editNumericRepeater(` + row.id + `, 'episodes_watched', this, `+ data +`, ` + row.episodes + `, -1)" onMouseUp="clearNumericRepeater()">-</button>
                            <button type="button" class="btn btn-sm btn-success" onMouseDown="editNumericRepeater(` + row.id + `, 'episodes_watched', this, `+ data +`, ` + row.episodes + `)" onMouseUp="clearNumericRepeater()">+</button>
                        </div>`;
                    }
                },
                {
                    title: 'Start',
                    data: "start_date",
                    render: function(data, type, row, meta) {
                        return `<text class="view-mode">` + (data == null ? "-" : data) + ` </text>
                             <input type="date" class="form-control edit-mode d-none" id="edit-start_date-` + row.id + `" value="` + data + `" oninput="onEdit(` + row.id + `, 'start_date', this)">
                        `;
                    }
                },
                {
                    title: 'Finish',
                    data: "finish_date",
                    render: function(data, type, row, meta) {
                        return `<text class="view-mode">` + (data == null ? "-" : data) + ` </text>
                             <input type="date" class="form-control edit-mode d-none" id="edit-finish_date-` + row.id + `" value="` + data + `" oninput="onEdit(` + row.id + `, 'finish_date', this)">
                        `;
                    }
                },
                {
                    title: 'Action',
                    render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-sm btn-primary" onclick="detail('` + row.anime_source_id + `')">
                                <i class="bi bi-info-circle"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteM('` + row.id + `')">
                               <i class="bi bi-trash"></i>
                            </button>
                        `;
                    }
                },
            ]
        })
    });

    function detail(anime_source_id) {
        location.href = "https://myanimelist.net/anime/" + anime_source_id;
    }

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function cancel_edit() {
        view_mode()
    }

    function confirm_edit() {
        view_mode()
    }

    function edit_mode() {
        $(".edit-mode").removeClass("d-none");
        $(".view-mode").addClass("d-none");
    }

    function view_mode() {
        $(".edit-mode").addClass("d-none");
        $(".view-mode").removeClass("d-none");
    }

    function modal() {
        $("#select-anime-page-1").removeClass("d-none");
        $("#next").removeClass("d-none");
        $("#select-anime-page-2").addClass("d-none");
        $("#submit").addClass("d-none");
    }

    function image_modal(image_obj) {
        $("#image-modal-image").attr('src', image_obj.getAttribute("src"));
        $("#image-modal-image").attr('alt', image_obj.getAttribute("alt"));
    }

    let searchInterval;

    function searchAnime(input) {
        clearInterval(searchInterval);
        searchInterval = setTimeout(() => {
            NProgress.start();

            $.ajax({
                url: source.base_url,
                type: "get",
                data: {
                    q: input.value
                },
                success: function(response) {
                    const animeDisplay = document.getElementById("anime-display");
                    animeDisplay.innerHTML = "";
                    apiResponse = response.data;
                    response.data.forEach((value, index) => {
                        let selected = false;
                        selectedAnime.forEach((val, idx) => {
                            if (val.mal_id == value.mal_id) {
                                selected = true;
                                return;
                            }
                        });
                        animeDisplay.innerHTML += animeDisplayGrid(value.images.jpg.image_url, value.title,
                            value.mal_id, selected);
                    });
                    NProgress.done();
                },
                error: function(xhr) {
                    toastr.error("<b>Error</b><br> Internal Server Error");
                    NProgress.done();
                }
            });
        }, 500);
    }

    function animeDisplayGrid(image, title, id, is_selected = false) {
        let sel = is_selected ? "bg-success" : "";
        return `
            <div class="col-sm-3 d-flex justify-content-center">
                <div class="mt-2 pt-2 ` + sel + `" style="width: 18rem; border-radius : 10px; cursor: pointer" onclick="selectAnime(this, '` + id + `')" id="` + id + `">
                    <div class="card-img-top d-flex justify-content-center px-2">
                        <img src="` + image + `" style="height: 200px; border-radius : 10px" alt="` + title + `'s cover image">
                    </div>
                    <div class="card-body" style="margin-top: -10px">
                        <h6>` + title + `</h6>
                    </div>
                </div>
            </div>
        `;
    }

    function selectAnime(context, id) {
        let value = {};
        apiResponse.forEach((val, idx) => {
            if (val.mal_id == id) {
                value = val;
                return;
            }
        });
        context.classList.toggle("bg-success");
        let addNew = true;
        selectedAnime.forEach((val, idx) => {
            if (val.mal_id == id) {
                selectedAnime.splice(idx, 1);
                addNew = false;
                return;
            }
        });
        if (addNew) {
            selectedAnime.push(value);
        }
        console.log(selectedAnime);
    }

    function showPage2() {
        if (selectedAnime.length < 1) {
            toastr.warning("<b>Warning</b><br> Select anime first!!");
            return;
        }
        $("#select-anime-page-1").addClass("d-none");
        $("#next").addClass("d-none");
        // processPage2Form()
        $("#select-anime-page-2").removeClass("d-none");
        $("#submit").removeClass("d-none");
        const animeDisplay2 = document.getElementById("anime-watch-details");
        animeDisplay2.innerHTML = "";
        selectedAnime.forEach((value, index) => {
            animeDisplay2.innerHTML += animeWatchDetailsLayout(index);
        });
    }

    function animeWatchDetailsLayout(index) {
        var anime = selectedAnime[index]
        return `    <div class="row border-bottom pt-3 pb-2" style="width: 100%">
                        <div class="col-sm-4 d-flex justify-content-center align-items-center px-1">
                            <div class="mt-2 pt-2" style="width: 18rem; border-radius : 10px; cursor: pointer">
                                <div class="card-img-top d-flex justify-content-center px-2">
                                    <img src="` + anime.images.jpg.image_url + `" style="height: 200px; border-radius : 10px" alt="` + anime.images.jpg.image_url + `'s cover image">
                                </div>
                                <div class="card-body" style="margin-top: -10px; padding-inline: 56px">
                                    <h6>` + anime.title + `</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="status-` + anime.mal_id + `" name="status-` + anime.mal_id + `">
                                    <option selected value="">--Select Status--</option>
                                    <option value="plan to watch">Plan to Watch</option>
                                    <option value="watching">Watching</option>
                                    <option value="completed">Completed</option>
                                    <option value="on-hold">On-Hold</option>
                                    <option value="dropped">Dropped</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Episodes Watched</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" min="0" id="episodes_watched-` + anime.mal_id + `" name="episodes_watched-` + anime.mal_id + `">
                                    <span class="input-group-text" id="basic-addon2">/ ` + anime.episodes + `</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date-` + anime.mal_id + `" name="start_date-` + anime.mal_id + `">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Finish Date</label>
                                <input type="date" class="form-control" id="finish_date-` + anime.mal_id + `" name="finish_date-` + anime.mal_id + `">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Comments</label>
                                <textarea class="form-control" id="comments-` + anime.mal_id + `" name="comments-` + anime.mal_id + `">
                                </textarea>
                            </div>
                        </div>
                    </div>`;
    }

    function processPage2Form() {
        userAnime = [];
        let res = true;
        selectedAnime.forEach(function(val, idx) {
            let id = val.mal_id;
            let status = document.getElementById("status-" + id).value;
            let episodes_watched = document.getElementById("episodes_watched-" + id).value;
            let start_date = document.getElementById("start_date-" + id).value;
            let finish_date = document.getElementById("finish_date-" + id).value;
            let comments = document.getElementById("comments-" + id).value;
            if (!status) {
                toastr.warning("<b>Warning</b><br> Status required");
                userAnime = [];
                res = false;
                return;
            }
            if (episodes_watched == "") {
                episodes_watched = 0;
            }
            let temp = {
                anime: val,
                input: {
                    status: status,
                    episodes_watched: episodes_watched,
                    start_date: start_date,
                    finish_date: finish_date,
                    comments: comments
                }
            };
            userAnime.push(temp);
        });
        return res;
    }

    function submitSelectedAnime() {
        NProgress.start();
        if (processPage2Form()) {
            $.ajax({
                url: base_url + "api/anime_list/list_new/",
                type: "post",
                data: JSON.stringify({
                    source_id: source.source_id,
                    data: userAnime
                }),
                contentType: "application/json; charset=utf-8",
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success("<b>Success</b><br>" + response.message);
                        $("#closee").click();
                    } else {
                        toastr.warning("<b>Warning</b><br>" + response.message);
                        toastr.success("<b>Success</b>");
                        $("#closee").click();
                    }
                    table.ajax.reload();
                    NProgress.done();
                },
                error: function(xhr) {
                    toastr.error("<b>Error</b><br> Internal Server Error");
                    NProgress.done();
                }
            });
        }
    }

    let autoIncrementPhase;
    let incrementInterval;

    function clearNumericRepeater(){
        clearTimeout(autoIncrementPhase);
        clearInterval(incrementInterval);
    }

    function editNumericRepeater(id, attr, ctx, initial_data, max_amount, increment = 1){
        editNumeric(id, attr, ctx, initial_data, max_amount, increment);
        autoIncrementPhase = setTimeout(() => {
            incrementInterval = setInterval(() => {
                editNumeric(id, attr, ctx, initial_data, max_amount, increment);
            }, 50);
        }, 500);
    }

    function editNumeric(id, attr, ctx, initial_data, max_amount, increment = 1){
        let value = ctx.value;
        let itemIndex = getEditQueueIndex(id);
        if(itemIndex < 0){
            editQueue.push({
                id: id,
            });
            itemIndex = editQueue.length - 1;
        }
        if(editQueue[itemIndex][attr] == undefined){
            editQueue[itemIndex][attr] = initial_data;
        }
        editQueue[itemIndex][attr] += increment;
        if(editQueue[itemIndex][attr] < 0){
            editQueue[itemIndex][attr] = 0;
        }
        if(editQueue[itemIndex][attr] > max_amount){
            editQueue[itemIndex][attr] = max_amount;
        }
        $("#edit-"+attr+"-"+id).html(editQueue[itemIndex][attr]);
        console.log(editQueue);
    }

    function onEdit(id, attr, ctx){
        let value = ctx.value;
        let itemIndex = getEditQueueIndex(id);
        if(itemIndex < 0){
            editQueue.push({
                id: id,
            });
            itemIndex = editQueue.length - 1;
        }
        editQueue[itemIndex][attr] = value;
        console.log(editQueue);
    }

    function getEditQueueIndex(id) {
        let index = -1;
        editQueue.forEach((val, idx) => {
            if(val.id == id){
                index = idx;
                return
            }
        });
        return index;
    }

    function deleteM(id) {
        Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success ms-3",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        }).fire({
            title: 'Confirmation',
            text: 'Are you sure to delete this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                NProgress.start();
                $.ajax({
                    url: base_url + "api/anime_list/delete/" + id + "/",
                    type: "post",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success("<b>Berhasil</b><br>" + response.message);
                            table.ajax.reload();
                            NProgress.done();
                        } else {
                            toastr.warning("<b>Peringatan</b><br>" + response.message);
                            table.ajax.reload();
                            NProgress.done();
                        }
                    },
                    error: function(xhr) {
                        toastr.error("<b>Error</b><br> Internal Server Error");
                        NProgress.done();

                    }
                });
            }
        });
    }
</script>