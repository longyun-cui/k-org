$(function() {


    var Province,City,District;
    Province = document.getElementById("Province");
    City = document.getElementById("City");
    District = document.getElementById("District");

    // 初始化
    // (function(){
    //     var ProvinceHtml = "";
    //     ProvinceHtml = "<option value=''>请先选择省</option>";
    //     region.forEach(function(index){
    //         ProvinceHtml += "<option value='"+index.provinceCode+"'>"+index.provinceName+"</option>";
    //     });
    //     Province.innerHTML = ProvinceHtml;
    //
    //
    // })()



    // Province.onchange = function(){
    //     var that = this;
    //     var CityHtml = "";
    //     CityHtml = "<option value=''>请先选择市</option>";
    //     // 初始化县
    //     region.forEach(function(index){
    //         if(index.provinceCode == that.value){
    //             index.mallCityList.forEach(function(child){
    //                 CityHtml += "<option value='"+child.cityCode+"'>"+child.cityName+"</option>";
    //             });
    //             City.innerHTML = CityHtml;
    //             return ;
    //         }
    //     });
    // };
    // City.onchange = function(){
    //     var that = this;
    //     var DistrictHtml = "";
    //     region.forEach(function(index){
    //         if(index.provinceCode == Province.value){
    //             index.mallCityList.forEach(function(child){
    //                 if(child.cityCode == that.value){
    //                     child.mallAreaList.forEach(function(sun){
    //                         DistrictHtml += "<option value='"+sun.areaCode+"'>"+sun.areaName+"</option>";
    //                     });
    //                     District.innerHTML = DistrictHtml;
    //                     return ;
    //                 }
    //             });
    //         }
    //     });
    // };




    var $province = $(".area_select_province");
    var $city = $(".area_select_city");
    var $district = $(".area_select_district");

    // 初始化
    (function(){
        var ProvinceHtml = "";
        ProvinceHtml = "<option value=''>请选择省</option>";
        region.forEach(function(index){
            ProvinceHtml += "<option value='"+index.provinceCode+"'>"+index.provinceName+"</option>";
        });
        $province.html(ProvinceHtml);
    })()

    $province.on('change', function() {
        var $that = $(this);
        var $select_box = $that.parents('.area_select_box');
        var $city = $select_box.find('.area_select_city');
        var $district = $select_box.find('.area_select_district');

        var CityHtml = "";
        var DistrictHtml = "";
        CityHtml = "<option value=''>请选择市</option>";
        CityHtml = "";
        // 初始化城市
        region.forEach(function(element){
            if(element.provinceCode == $that.val()){
                element.mallCityList.forEach(function(child, index){
                    CityHtml += "<option value='"+child.cityCode+"'>"+child.cityName+"</option>";
                    if(index == 0)
                    {
                        child.mallAreaList.forEach(function(sun){
                            DistrictHtml += "<option value='"+sun.areaCode+"'>"+sun.areaName+"</option>";
                        });
                    }
                });
            }
        });
        $city.html(CityHtml);
        $district.html(DistrictHtml);
        console.log($city.val());
        // $district.html("<option value=''>请先选择市</option>");
        return ;
    });

    $city.on('change', function() {
        var $that = $(this);
        var $select_box = $that.parents('.area_select_box');
        var $province = $select_box.find('.area_select_province');
        var $district = $select_box.find('.area_select_district');

        var DistrictHtml = "";
        region.forEach(function(element){
            if(element.provinceCode == $province.val()){
                element.mallCityList.forEach(function(child, index){
                    if(child.cityCode == $that.val()){
                        child.mallAreaList.forEach(function(sun){
                            DistrictHtml += "<option value='"+sun.areaCode+"'>"+sun.areaName+"</option>";
                        });
                    }
                });
            }
        });
        $district.html(DistrictHtml);
        return ;
    });


});

