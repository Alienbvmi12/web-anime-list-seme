<style>

</style>
<h2>Anime List</h2>
<div class="mt-4">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" onclick="modal()">Add New</button>
    <button type="button" class="btn btn-warning view-mode" onclick="edit_mode()">Edit</button>
    <button type="button" class="btn btn-info edit-mode d-none" onclick="cancel_edit()">Cancel</button>
    <button type="button" class="btn btn-success edit-mode d-none" onclick="confirm_edit()">Save</button>
    <table id="main-table" class="table table-striped" style="width:100%">
    </table>
</div>

<div class="modal fade" id="modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">
                    Add anime
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="select-anime-page-1">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-9">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input id="search-anime" type="search" class="form-control" placeholder="Search..." oninput="searchAnime(this)">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-primary" style="width: 100%;">Advanced Search</button>
                        </div>
                    </div>
                    <div class="row mt-4" style="height: 50vh; overflow: auto;" id="anime-display">

                    </div>
                </div>
                <div id="select-anime-page-2" class="d-none">
                    <div style="height: 70vh; overflow-y: auto; overflow-x: hidden; width: 100%" id="anime-watch-details">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closee">
                    Close
                </button>
                <button id="next" type="button" class="btn btn-primary" onclick="showPage2()">Next</button>
                <button id="submit" type="button" class="btn btn-primary" onclick="submitSelectedAnime()">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-image" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-grid" style="place-items: center">
                <img src="" alt="" id="image-modal-image" style="object-fit: contain">
            </div>
        </div>
    </div>
</div>