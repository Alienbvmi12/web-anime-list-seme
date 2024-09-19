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


    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "api/anime_list/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
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
                        return "<img style=\"width:200px\">" + data + "</img>";
                    }
                },
                {
                    title: 'Title',
                    data: "title"
                },
                {
                    title: 'Status',
                    data: "status"
                },
                {
                    title: 'Episodes Watched',
                    data: "episodes_watched",
                    render: function(data, type, row, meta) {
                        return data + " / " + row.episodes + `
                        <div class="btn-group ms-1" role="group">
                            <button type="button" class="btn btn-danger">-</button>
                            <button type="button" class="btn btn-success">+</button>
                        </div>`;
                    }
                },
                {
                    title: 'Start',
                    data: "start_date",
                    render: function(data, type, row, meta) {
                        return data == null ? "-" : data;
                    }
                },
                {
                    title: 'Finish',
                    data: "finish_date",
                    render: function(data, type, row, meta) {
                        return data == null ? "-" : data;
                    }
                },
                {
                    title: 'Action',
                    render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-primary" onclick="detail('` + row.anime_id + `')">
                                <i class="fa-solid fa-info"></i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteM('` + row.id + `')">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        `;
                    }
                },
            ]
        })
    });

    function modal() {
        $("#select-anime-page-1").removeClass("d-none");  
        $("#next").removeClass("d-none");  
        $("#select-anime-page-2").addClass("d-none");  
        $("#submit").addClass("d-none");  
        selectedAnime = [];
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

    function showPage2(){
        $("#select-anime-page-1").addClass("d-none");  
        $("#next").addClass("d-none");  
        $("#select-anime-page-2").removeClass("d-none");  
        $("#submit").removeClass("d-none");  
    }

    function submitSelectedAnime(){
        NProgress.start();
        $.ajax({
            url: base_url + "api/anime_list/list_new/",
            type: "post",
            data: JSON.stringify({
                source_id: source.source_id,
                data: selectedAnime
            }),
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    toastr.success("<b>Success</b><br>" + response.message);
                    $("#closee").click();
                } else {
                    toastr.warning("<b>Warning</b><br>" + response.message);
                }
                NProgress.done();
            },
            error: function(xhr) {
                toastr.error("<b>Error</b><br> Internal Server Error");
                NProgress.done();
            }
        });
    }
</script>