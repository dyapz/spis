  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#"><i class="fa-solid fa-house fa-sm"></i> Home</a></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-person-cane fa-sm"></i> Client List</a></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addNew"><i class="fa-solid fa-plus fa-sm"></i> New</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown link
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="#">Menu 1</a></li>
            <li><a class="dropdown-item" href="#">Menu 2</a></li>
            <li><a class="dropdown-item" href="#">Menu 3</a></li>
          </ul>
        </li>
      </ul>
      <div class="ms-auto">
        <ul class="navbar-nav">
          <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Fullname
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="right: 0; left: auto;">
              <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear fa-sm fa-fw"></i> Profile</a></li>
              <li><a class="dropdown-item" href="#"><i class="fa-solid fa-key fa-sm fa-fw"></i> Change Password</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>




<!-- Add -->
  <!-- Forgot Password -->
  <div class="modal fade" id="addNew" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addNewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header"  style="background-color: #20B2AA;">
          <h1 class="modal-title fs-5 text-light" id="addNewLabel">SOCIAL PENSION FORM<br><p style="font-size: 17px; margin-bottom: -10px"><span class="fw-bold">I. IDENTIFYING INFORMATION</span> (Pagkilala ng Impormasyon)</p></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="" method="POST" autocomplete="off" class="row g-3">
            
                <p class="text-color-custom"><span class="fw-bold">NAME</span> (Pangalan)<span class="fw-bold">:</span></p>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientInfo_lname" placeholder="" required>
                        <label for="sp_clientInfo_lname" class="form-label">Last Name (Apelyido)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientInfo_fname" placeholder="" required>
                        <label for="sp_clientInfo_fname" class="form-label">First Name (Unang Pangalan)</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientInfo_mname" placeholder="">
                        <label for="sp_clientInfo_mname" class="form-label">Middle Name (Gitnang Pangalan)</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientInfo_ename" placeholder="">
                        <label for="sp_clientInfo_ename" class="form-label">Ext. (III, Jr.)</label>
                    </div>
                </div>

                <hr style="margin-top: -10px">
                <p style="margin-top: -10px" class="text-color-custom"><span class="fw-bold">MOTHER'S MAIDEN NAME</span> (Pangalan ng ina sa pagkadalaga)<span class="fw-bold">:</span></p>

                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientMmn__lname" placeholder="" required>
                        <label for="sp_clientMmn__lname" class="form-label">Last Name (Apelyido)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_clientMmn__fname" placeholder="" required>
                        <label for="sp_clientMmn__fname" class="form-label">First Name (Unang Pangalan)</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sp_mmn_mname" placeholder="">
                        <label for="sp_mmn_mname" class="form-label">Middle Name (Gitnang Pangalan)</label>
                    </div>
                </div>

                <hr style="margin-top: -10px">
                <p style="margin-top: -10px" class="text-color-custom"><span class="fw-bold">PERMANENT ADDRESS</span> (Permanenteng Tirahan)<span class="fw-bold">:</span></p>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sitio" placeholder="" required>
                        <label for="sitio" class="form-label">Sitio/House No./Purok/Street</label>
                    </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="brgy_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Barangay</option>
                      </select>
                      <label for="brgy_id" class="form-label">Barangay</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                      <select name="" class="form-select" id="muni_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Municipality</option>
                      </select>
                      <label for="muni_id" class="form-label">City/Municipality (Lungsod)</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="prov_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Province</option>
                      </select>
                      <label for="prov_id" class="form-label">Province (Lalawigan)</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="region_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Region</option>
                      </select>
                      <label for="region_id" class="form-label">Region (Rehiyon)</label>
                  </div>
                </div>

                <hr style="margin-top: -10px">
                <p style="margin-top: -10px" class="text-color-custom"><span class="fw-bold">PRESENT ADDRESS</span> (Kasalukuyang Tirahan)<span class="fw-bold">:</span></p>

                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <label class="form-check-label" for="flexCheckDefault">
                    Permanent Address is same as Present Address.
                  </label>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="" class="form-control" id="sitio" placeholder="" required>
                        <label for="sitio" class="form-label">Sitio/House No./Purok/Street</label>
                    </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="brgy_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Barangay</option>
                      </select>
                      <label for="brgy_id" class="form-label">Barangay</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                      <select name="" class="form-select" id="muni_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Municipality</option>
                      </select>
                      <label for="muni_id" class="form-label">City/Municipality (Lungsod)</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="prov_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Province</option>
                      </select>
                      <label for="prov_id" class="form-label">Province (Lalawigan)</label>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                      <select name="" class="form-select" id="region_id" required>
                        <option value="" disabled selected></option>
                        <option value="1">Region</option>
                      </select>
                      <label for="region_id" class="form-label">Region (Rehiyon)</label>
                  </div>
                </div>

            </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-custom"><i class="fa-solid fa-check"></i> Save</button>
        </form>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Cancel</button>
        </div>
      </div>
    </div>
  </div>