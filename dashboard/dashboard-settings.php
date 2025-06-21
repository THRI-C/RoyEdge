<?php include 'include/sidebar.php' ?>

    <div class="main-content">
    <?php include 'include/navbar.php' ?>

        <div class="px-3">
        <section class="settings">
                <h2>Profile information</h2>
                <form action="https://sealcorelines.com/settings" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="k6MQwqBXs9IdTjvxftWvN1VKq0wX1SGjniUrT2dw" autocomplete="off">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <div class="mb-3">
                        <div class="prof-image mx-auto" style="width: 200px; height: 200px; overflow: hidden; border-radius: 100%; box-shadow: 3px 5px 10px #e1e1e1;">
                            <img src="https://sealcorelines.com/assets/img/illustrations/boy-app-academy.png" alt="Profile" class="w-100 h-100" style="object-fit: cover;">
                        </div>
                        
                        <div class="mb-3 mt-3">
                            <label for="profile_image" class="form-label">Profile Image (Max. File Size: 1.0 MiB)</label>
                            <input type="file" name="profile_image" id="profile_image" class="form-control">
                        </div>
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="Chukwuma Chimaroke" required="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="triplecoffice22@gmail.com" readonly="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="07048430360" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="text" name="date_of_birth" id="date_of_birth" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Address Line</label>
                        <input type="text" name="address_line" id="address_line" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" id="city" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" name="state" id="state" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="lga" class="form-label">LGA</label>
                        <input type="text" name="lga" id="lga" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" name="country" id="country" value="" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-secondary">Update Profile</button>
                </form>
            </section>


    </div>
    </div>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>