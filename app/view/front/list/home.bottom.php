<script>
    const base_url = '<?= base_url() ?>';
    const source = {
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

    function modal(type, context) {
        if (type == "create") {
            $("#nama_produk").val("");
            $("#harga").val("");
            $("#stok").val("");
            $("#status").val("1");
            $("#stok-container").removeClass("d-none");

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Petugas");
        } else {
            let row = context.parentNode.parentNode.getElementsByTagName("td");
            edit_id = row[0].innerHTML;
            $("#nama_produk").val(row[1].innerHTML);
            $("#harga").val(_decode_rupiah(row[2].innerHTML));
            $("#status").val(row[4].innerHTML == "Aktif" ? 1 : 0);
            // $("#stok").val(row[3].innerHTML);

            $("#stok-container").addClass("d-none");

            $("#submit").attr("onclick", "edit(this)");
            $("#modal-title").html("Edit Data Petugas");
        }
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
                    <div class="card-img-top d-flex justify-content-center">
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
</script>