function loadProvinces() {
    var selectProvince = document.getElementById("province");
  
    // Gọi API để lấy danh sách tỉnh/thành phố
    axios.get("https://vapi.vnappmob.com/api/province").then(function(response) {
      var provinces = response.data.results;
  
      provinces.forEach(function(province) {
        var option = document.createElement("option");
        option.value = province.province_id;
        option.text = province.province_name;
        selectProvince.add(option);
      });
    });
  }
  
  function loadDistricts() {
    var selectProvince = document.getElementById("province");
    var selectDistrict = document.getElementById("district");
    var selectedProvinceId = selectProvince.value;
  
    // Xóa tất cả các option hiện tại trong selectDistrict
    selectDistrict.innerHTML = "<option value=''>Chọn quận/huyện</option>";
  
    if (selectedProvinceId) {
      // Gọi API để lấy danh sách quận/huyện của tỉnh/thành phố
      axios.get(`https://vapi.vnappmob.com/api/province/district/${selectedProvinceId}`).then(function(response) {
        var districts = response.data.results;
  
        districts.forEach(function(district) {
          var option = document.createElement("option");
          option.value = district.district_id;
          option.text = district.district_name;
          selectDistrict.add(option);
        });
      });
    }
  }
  
  // Gọi hàm loadProvinces khi trang được tải
  document.addEventListener("DOMContentLoaded", function() {
    loadProvinces();
  });
  