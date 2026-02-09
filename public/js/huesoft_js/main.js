$(function () {
    const overlay = $("#overlay");
    $("nav .nav #close-menu-btn").on("click", function (event) {
        $("nav .nav").removeClass("active");
        overlay.removeClass("active");
    });

    $("#open-menu-btn").on("click", function (event) {
        $("nav .nav").addClass("active");
        overlay.addClass("active");
    });

    overlay.on("click", function (event) {
        $("nav .nav").removeClass("active");
        overlay.removeClass("active");
    });

    const province_id = $("#province").data("value");
    if (province_id) {
        loadDistricts(province_id);
    }
    // const district_id = $("#district").data("value");
    // if (district_id) {
    //     loadWards(district_id);
    // }
    const ward_id = $("#ward").data("value");
    $.ajax({
        url: "/api/provinces",
        method: "GET",
        success: function (data) {
            const provincesSelect = $("#province");

            provincesSelect.find("option:not(:first)").remove();
            provincesSelect.append(new Option("Chọn tỉnh/thành phố", ""));
            data.forEach(function (province) {
                const option = new Option(province.name, province.id);
                if (province.id == province_id) {
                    option.selected = true;
                }
                provincesSelect.append(option);
            });
        },
        error: function (xhr, status, error) {
            alert("Lỗi khi lấy dữ liệu tỉnh:", error);
        },
    });

    function loadDistricts(provinceId) {
        const districtsSelect = $("#district");
        districtsSelect.find("option:not(:first)").remove();

        if (districtsSelect.find('option[value=""]').length === 0) {
            districtsSelect.append(new Option("Chọn quận/huyện", ""));
        }
        let district_id = $("#district").data("value");
        $.ajax({
            url: `/api/districts/${provinceId}`,
            method: "GET",
            success: function (data) {
                data.forEach(function (district) {
                    const option = new Option(district.name, district.id);
                    if (district.id == district_id) {
                        option.selected = true;
                    }
                    districtsSelect.append(option);
                });

                if (district_id) {
                    loadWards(district_id);
                    district_id = null;
                }
            },
            error: function (xhr, status, error) {
                alert("Lỗi khi lấy dữ liệu quận/huyện: " + error);
            },
        });
    }
    function removeWardOption() {
        $("#ward").find("option:not(:first)").remove();
        $("#ward").val("");
    }
    function loadWards(districtId) {
        const wardsSelect = $("#ward");
        wardsSelect.find("option:not(:first)").remove();
        const ward_id = $("#ward").data("value");
        if (wardsSelect.find('option[value=""]').length === 0) {
            wardsSelect.append(new Option("Chọn xã/phường", ""));
        }

        $.ajax({
            url: `/api/wards/${districtId}`,
            method: "GET",
            success: function (data) {
                data.forEach(function (ward) {
                    const option = new Option(ward.name, ward.id);
                    if (ward.id == ward_id) {
                        option.selected = true;
                    }
                    wardsSelect.append(option);
                });
            },
            error: function (xhr, status, error) {
                alert("Lỗi khi lấy dữ liệu xã/phường: " + error);
            },
        });
    }

    $("#province").on("change", function () {
        const provinceId = $(this).val();
        $("#province").removeAttr("data-value").removeData("value");
        $("#district").removeAttr("data-value").removeData("value");
        $("#ward").removeAttr("data-value").removeData("value");
        removeWardOption();
        if (provinceId) {
            loadDistricts(provinceId);
        } else {
            $("#district, #ward").find("option:not(:first)").remove();
            $("#district, #ward").val("");
        }
    });

    $("#district").on("change", function () {
        const districtId = $(this).val();
        $("#province").removeAttr("data-value").removeData("value");
        $("#district").removeAttr("data-value").removeData("value");
        $("#ward").removeAttr("data-value").removeData("value");
        if (districtId) {
            loadWards(districtId);
        } else {
            $("#ward").find("option:not(:first)").remove();
            $("#ward").val("");
        }
    });

    

    

    const validateInputMin6 = function (element) {
        element.addEventListener("input", function (e) {
            const input = $(e.target);
            const validFeedback = $(this).siblings(".valid-feedback");
            const invalidFeedback = $(this).siblings(".invalid-feedback");
            if (input.val().trim().length < 6) {
                validFeedback.css("display", "none");
                invalidFeedback.css("display", "inline-block");
                input.removeClass("input-valid");
                input.addClass("input-invalid");
                console.log("< 6");
            } else {
                validFeedback.css("display", "inline-block");
                invalidFeedback.css("display", "none");
                input.removeClass("input-invalid");
                input.addClass("input-valid");
            }
        });
    };

    const validateInputEqual10 = function (element) {
        element.addEventListener("input", function (e) {
            const input = $(e.target);
            const validFeedback = $(this).siblings(".valid-feedback");
            const invalidFeedback = $(this).siblings(".invalid-feedback");
            if (input.val().trim().length !== 10) {
                validFeedback.css("display", "none");
                invalidFeedback.css("display", "inline-block");
                input.removeClass("input-valid");
                input.addClass("input-invalid");
                console.log("< 6");
            } else {
                validFeedback.css("display", "inline-block");
                invalidFeedback.css("display", "none");
                input.removeClass("input-invalid");
                input.addClass("input-valid");
            }
        });
    };

    const validateInputRegex = function (element) {
        element.addEventListener("input", function (e) {
            const input = $(e.target);
            const validFeedback = $(this).siblings(".valid-feedback");
            const invalidFeedback = $(this).siblings(".invalid-feedback");
            const regex = /^[a-zA-Z0-9]+$/;

            if (!regex.test(input.val())) {
                validFeedback.css("display", "none");
                invalidFeedback.css("display", "inline-block");
                input.removeClass("input-valid");
                input.addClass("input-invalid");
                return;
            }

            if (input.val().trim().length < 6) {
                validFeedback.css("display", "none");
                invalidFeedback.css("display", "inline-block");
                input.removeClass("input-valid");
                input.addClass("input-invalid");
                return;
            }

            validFeedback.css("display", "inline-block");
            invalidFeedback.css("display", "none");
            input.removeClass("input-invalid");
            input.addClass("input-valid");
        });
    };

    const inputs = $("input");

    if (inputs) {
        inputs.each(function (index) {
            $(this).on("input", function () {
                const validFeedback = $(this).siblings(".valid-feedback");
                const invalidFeedback = $(this).siblings(".invalid-feedback");
                if ($(this).val().trim().length > 0) {
                    validFeedback.css("display", "inline-block");
                    invalidFeedback.css("display", "none");
                } else {
                    validFeedback.css("display", "none");
                    invalidFeedback.css("display", "inline-block");
                }
            });
        });
    }

    const username = document.getElementById("uname");
    if (username) {
        validateInputRegex(username);
    }

    const pwd = document.getElementById("pwd");
    if (pwd) {
        validateInputMin6(pwd);
    }

    const pwd_confim = document.getElementById("password_confirmation");
    if (pwd_confim) {
        validateInputMin6(pwd_confim);
    }

    const chagne_pwd = document.getElementById("change_pwd");
    if (chagne_pwd) {
        validateInputMin6(chagne_pwd);
    }

    const old_pwd = document.getElementById("old_pwd");
    if (old_pwd) {
        validateInputMin6(old_pwd);
    }

    const phone_number = document.getElementById("phone_number");
    if (phone_number) {
        validateInputEqual10(phone_number);
    }
});
