var fileElement = '';
function initMainDataTable(){
    if ($('.table-datatable').length) {
        if ($('.table-datatable').hasClass('table-fixed')) {
          $('.table-fixed').DataTable({
            scrollY: function () {
              return `${$($(this).find('table')).attr('data-height')}px`;
            },
            deferRender: true,
            scroller: true,
          });
        } else $('.table-datatable').DataTable();
    }
}
function initSelectPicker(){
    $('select.selectpicker').selectpicker();
    $('select.selectpicker-class').selectpicker();
}

function hideFilters(){
    $('#table_search1').keyup(function () {
        $('.dataTables_wrapper input[type="search"]').val($('#table_search').val());
        var $search = $('.dataTables_wrapper').find('input[type="search"]');
        var e = $.Event('keyup');
        $search.trigger(e);
    });

    $('#table_search1').keyup(function () {
        var value = $(this).val();
        var patt =new RegExp(value,"i");
        console.log(patt);
        $('table.classic').find('tr').each(function() {
            if (!($(this).find('td').text().search(patt) >= 0)) {
                //$(this).not('.myHead').hide();
                $(this).hide();
            }
            if (($(this).find('td').text().search(patt) >= 0)) {
                $(this).show();
            }
        });
    });
}
if ($('table[data-height]').length) {
    $('table[data-height]').each(function () {
        let height =
            $(this).closest('.table-container').height() -
            $(this).closest('.table-container').find('.row').outerHeight(true) -
            32;
            if (window.innerHeight < $(this).closest('.table-container').height()) {
                height =
                  window.innerHeight -
                  $(this).closest('.table-container').find('.row').outerHeight(true) -
                  32;
                if ($(this).hasClass('tree')) {
                    height = height - 130;
                }
            }
        if ($(this).closest('.table-container').hasClass('pb-32')) {
            height = height - 130;
        }
        if ($(this).closest('.table-container').find('.search-wrap').length) {
            height = height - 70;
        }
        if (
            $(this).closest('.table-container').hasClass('border-left-before') &&
            window.innerWidth >= 1500
        ) {
            height = height + (window.innerWidth - 1500) * 0.35;
        }else if ($(this).closest('.table-container').hasClass('border-left-before')) height = height - 100;
        if (height <= 0 || height <= 150) {
            height = '';
        }

        $(this).attr('data-height', height);
        //$(this).css('height', height);
    });
}

if ($('table[data-tree-enable]').length) {
    $('table[data-tree-enable]').each(function () {
        if ($(this).hasClass('add-height')) {
            let height =
                $(this).closest('.table-container').height() -
                $(this).closest('.table-container').find('.row').outerHeight(true) -
                32;
            $(this).attr('data-height', height);
        }
        $(this).bootstrapTable().treegrid({ initialState: 'collapsed' });

        $(document)
            .off('click', '.treegrid-collapsed, .treegrid-expanded')
            .on('click', '.treegrid-collapsed, .treegrid-expanded', function (e) {
                if ($(e.target).prop('tagName') == 'TD') {
                    $($(e.target).closest('tr')).find('.treegrid-expander').trigger('click');
                }
            });
    });
}

jQuery(document).ready(function ($) {
    //switch off preloader
    function spinerOff() {
        $('#spinerWrap').addClass('d-none').removeClass('d-flex');
        $(document.body).removeClass('overflow-hidden');
    }

    spinerOff();

    // function for show password

    $('.toggle-password').click(function () {
        $(this).toggleClass('eye-slash');
        var input = $($(this).attr('toggle'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    // function to move in next tab

    var elts = document.getElementsByClassName('text-code');
    Array.from(elts).forEach(function (elt) {
        elt.addEventListener('keyup', function (event) {
            if ((((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) && elt.value.length == 1) && elt.nextElementSibling) {
                elt.nextElementSibling.focus();
            }
        });
    });
    // ************************ Drag and drop ***************** //
    function dragsDocument(element) {
        let dropArea = element;

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover', 'mouseover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        ['dragleave', 'drop', 'mouseout'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        if(element.getElementsByClassName('fileElem').length){
          element.getElementsByClassName('fileElem')[0].addEventListener('change', handleFiles, false);
        }
        let url;

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropArea.classList.add('highlight');
        }

        function unhighlight(e) {
            dropArea.classList.remove('highlight');
        }

        function handleFiles(files) {
            if (files.target) {
                files = files.target.files;
            }
            files = [...files];
            files.forEach(uploadFile);
        }

        function handleDrop(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            handleFiles(files);
        }

        function previewFile(file, url) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function () {
                let img = document.createElement('img');
                img.src = reader.result;
                dropArea.classList.add('full');

                element.querySelector('.gallery').innerHTML = `<img src="${img.src}" data-url="${url}">`;
            };
        }

        function uploadFile(file, i) {
            // var url = 'https://api.cloudinary.com/v1_1/dlv7otqvk/image/upload';
            // var xhr = new XMLHttpRequest();
            // var formData = new FormData();
            // xhr.open('POST', url, true);
            // xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            // formData.set('upload_preset', 'ml_default');
            // formData.set('file', file);
            // xhr.send(formData);
            // xhr.onload = function () {
            //   var a = JSON.parse(xhr.response);
            //   url = a['url'];

            //   previewFile(file, url);
            // };
            if($(dropArea).find('input').length){
                fileElement = $(dropArea).find('input');
            }
            if ($(dropArea).hasClass('open-cropper')) {
                UploadCrop(dropArea, file);
            } else {
                previewFile(file);
            }
        }
    }
    let arr = Array.prototype.slice.call(document.getElementsByClassName('drop-area-img-upload'));
    for (let i = 0; i < arr.length; i++) {
        dragsDocument(arr[i]);
    }
    // document.addEventListener("livewire:load", function (event) {
    // });

    // navigation on registration page. Can be deleted after implementing logic
/*
    $('.button-next').click(function () {
        const nextTabLinkEl = $('#nav-tab .active').closest('.nav-link').next('.nav-link')[0];
        const nextTab = new bootstrap.Tab(nextTabLinkEl);
        nextTab.show();

        if ($('.active').index() == 0) {
            $('.nav-prev').addClass('invisible');
        } else $('.nav-prev').removeClass('invisible');
    });

    $('.nav-prev').click(function () {
        const prevTabLinkEl = $('#nav-tab .active').closest('.nav-link').prev('.nav-link')[0];
        const prevTab = new bootstrap.Tab(prevTabLinkEl);
        prevTab.show();

        if ($('.active').index() == 0) {
            $('.nav-prev').addClass('invisible');
        } else $('.nav-prev').removeClass('invisible');
    });

    var triggerTabList = [].slice.call(document.querySelectorAll('#nav-tab a'));
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);

        triggerEl.addEventListener('click', function (event) {
            event.preventDefault();
            tabTrigger.show();
            if ($('.active').index() == 0) {
                $('.nav-prev').addClass('invisible');
            } else $('.nav-prev').removeClass('invisible');
        });
    });*/

    // initial for toggle menu
    function drawStuff() {
        if ($(window).width() < 992) {
            $('.asidebar').addClass('collapse').removeClass('fliph left sidebar');
            $('.asidebar').attr('id', 'navigation');
            $('.animated-hamburger').removeClass('open');
        } else if ($(window).width() >= 992) {
            $('.asidebar').addClass('no-anim');
            $('.asidebar').removeClass('collapse');
            $('.asidebar').addClass('sidebar left');
            $('.asidebar').attr('id', '');
            setTimeout(() => $('.asidebar').removeClass('no-anim'), 500);
        }
        $('.navbar-toggler-button').on('click', function () {
            $('.animated-hamburger').toggleClass('open');
        });
        $('#navigation').on('hidden.bs.collapse', function () {
            setTimeout(() => $('#navigation').removeClass('show'), 200);
        });
    }

    drawStuff();
    $(window).resize(function () {
        drawStuff();
    });

    // add vh for mobile (needs for responsive, when bar with url hidding)
    if ($(window).width() < 768) {
        (function init100vh() {
            function setHeight() {
                var vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }
            setHeight();
            window.addEventListener('resize', setHeight);
        })();
    }

    // main chart
    function mainChart() {
        let maxDay = 14;
        let datas = [
            { x: 0, y: 30, date: '1/1/22' },
            { x: 1, y: 18, date: '2/1/22' },
            { x: 2, y: 39, date: '3/1/22' },
            { x: 3, y: 70, date: '4/1/22' },
            { x: 4, y: 79, date: '5/1/22' },
            { x: 5, y: 65, date: '6/1/22' },
            { x: 6, y: 90, date: '7/1/22' },
            { x: 7, y: 30, date: '8/1/22' },
            { x: 8, y: 60, date: '9/1/22' },
            { x: 9, y: 60, date: '10/1/22' },
            { x: 10, y: 50, date: '11/1/22' },
            { x: 11, y: 79, date: '12/1/22' },
            { x: 12, y: 65, date: '13/1/22' },
            { x: 13, y: 90, date: '14/1/22' },
        ];

        var ctx = document.getElementById('mainChart').getContext('2d');

        const footer = tooltipItems => {
            // console.log(tooltipItems[0].label);
            return `${labels1[tooltipItems[0].label % 7]}, ${tooltipItems[0].raw.date}`;
        };

        const title = tooltipItems => {
            return '$' + tooltipItems[0].formattedValue;
        };
        let labels1 = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

        var gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(205,242,205,1)');
        gradient.addColorStop(1, 'rgba(205,242,205,0)');

        let mainSettings = {
            type: 'line',
            data: {
                labels: [...Array(maxDay).keys()],
                datasets: [
                    {
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#55D168',
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 10,
                        hitRadius: 5,
                        data: datas,
                        pointStyle: function (context) {
                            var img = new Image(20, 20);
                            img.src = './img/ellipse_chart.png';
                            return img;
                        },
                    },
                ],
            },
            interaction: {
                intersect: false,
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleFont: {
                            weight: 'bold',
                            size: 14,
                            family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                        },
                        titleColor: '#000',
                        footerFont: {
                            weight: 'normal',
                            size: 12,
                            family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                        },
                        footerColor: '#000',
                        displayColors: false,
                        bodyFont: { size: 0 },
                        padding: 11,
                        cornerRadius: 8,
                        borderColor: '#E1E1E1',
                        borderWidth: 1,
                        callbacks: {
                            footer: footer,
                            title: title,
                        },
                    },
                    parsing: {
                        xAxisKey: 'x',
                    },
                },
                responsive: true,
                tension: 0.5,
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            callback: function (value, index, ticks) {
                                return '$' + value;
                            },
                            color: '#000000',
                            font: {
                                size: 14,
                                family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                            },
                        },
                        border: {
                            color: '#ff000000',
                        },
                        grid: {
                            color: '#E8E9EA',
                            borderCapStyle: 'round',
                            borderDash: [9, 9],
                            tickColor: '#ff000000',
                            borderWidth: 0,
                            lineWidth: 2,
                        },
                    },
                    x: {
                        scales: {
                            type: 'linear',
                        },
                        grid: {
                            display: false,
                            borderWidth: 0,
                        },
                        ticks: {
                            color: '#000000',
                            font: {
                                size: 14,
                                family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                            },
                            callback: function (value, index, ticks) {
                                return labels1[value % 7];
                            },
                        },
                    },
                },
            },
        };
        let chart = new Chart(ctx, mainSettings);
        // here the functions for updating
        $('.btn').click(function () {
            function addData(chart, label, data) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach(dataset => {
                    dataset.data.push(data);
                });
                chart.update();
            }

            function removeData(chart) {
                chart.data.labels.pop();
                chart.data.datasets.forEach(dataset => {
                    dataset.data.pop();
                });
                chart.update();
            }
        });
    }

    if ($('#mainChart').length) {
        mainChart();
    }

    // search in table from default input

    // buttons plus and minus

    $('.btn-number').click(function (e) {
        e.preventDefault();
        let fieldName = $(this).attr('data-field');
        let type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }
            } else if (type == 'plus') {
                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }
            }
        } else {
            input.val(0);
        }
    });
    $('#Expiration_root').click(function (e) {
        e.preventDefault();
        console.log('test');
    });

    function setToddayExpirationDate(){
        var date=new Date();
        var month = date.getMonth()+1;
        var day = date.getDate();

        var today = ((''+month).length<2 ? '0' : '') + month + '/' +
                    ((''+day).length<2 ? '0' : '') + day + '/' +
                    date.getFullYear();

        $("#datepicker-expiration").attr("data-date-start-date", today);
    }

    if ($('.datepicker').length || $('[data-provide="datepicker"]').length) {
        setToddayExpirationDate();
        let DateSettings = {
            title: 'Select expiration date',
            format: 'dd/mm/yyyy',
        };
        $.fn.datepicker.dates['en'] = {
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysMin: ['s', 'm', 't', 'w', 't', 'f', 's'],
            months: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
            monthsShort: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
            ],
            today: 'Today',
            clear: 'Clear',
            format: 'mm/dd/yyyy',
            titleFormat: 'MM yyyy',
            weekStart: 0
        };
        $('#datepicker-expiration').trigger('click');
        $('#datepicker-expiration')
            .datepicker(DateSettings)
            .on('changeDate', function (e) {
                var newday = new Date(e.date);
                var dd = newday.getDate();
                var mm = newday.getMonth() + 1;
                var yyyy = newday.getFullYear();
                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }
                newday = dd + '/' + mm + '/' + yyyy;
                $(e.currentTarget).siblings().val(newday);
            });
        $(document)
            .off('click', '#confirmation-btn')
            .on('click', '#confirmation-btn', function () {
                let value = $(this).closest('.modal-body').find('input[type="hidden"]').val();
                let input = $(this).attr('data-input');

                $(this).closest('.modal').find('.btn-close').trigger('click');
                $(input).val(value);
            });
        $(document)
            .off('click', '#Expiration')
            .on('click', '#Expiration', function () {
                $($(this).attr('data-linked-input')).datepicker('setDate', $(this).val());
            });
    }
    if ($('.edit-input').length) {
        $('.edit-input').each(function () {
            $(this).click(function (e) {
                let input = $(this).closest('.row').find('.editable-input');
                if (input.attr('readonly') == 'readonly') {
                    input.attr('readonly', false);
                    input.focus();
                } else input.attr('readonly', true);
                $(this).toggleClass('button-secondary button-green');
            });
        });
    }
    // function open cropper after uploading
    function UploadCrop(dropArea, file) {
        let element = $(dropArea).siblings('#container-crop')[0];
        if(['png', 'gif', 'bmp', 'svg','jpg', 'jpeg'].indexOf(file.name.split('.')[1]) != -1){
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function () {
                let img = document.createElement('img');
                img.src = reader.result;
                showCropper(img);
            };
        }
        fileElement.val('');
        function showCropper(img) {
            $(dropArea).addClass('visually-hidden');
            $(element).toggleClass('visually-hidden');
            $('.crop-image-finish').toggleClass('disabled');
            let cropElement = $(element).cropme({
                container: {
                    width: 400,
                    height: 300,
                },
                viewport: {
                    width: 220,
                    height: 220,
                    type: 'circle',
                    border: {
                        width: 0,
                        enable: true,
                        color: '#fff',
                    },
                },
                zoom: {
                    enable: true,
                    mouseWheel: true,
                    slider: true,
                },
                rotation: {
                    slider: false,
                    enable: false,
                    position: 'left',
                },
                transformOrigin: 'viewport',
            });
            cropElement.cropme('bind', {
                url: img.src, //here you can change image that you use
            });
            $('.crop-image-finish').on('click', testing);
            function testing() {
                cropElement.cropme('crop', {}).then(function (res) {
                    // console.log('resized', res);
                    window.livewire.emit('saveProfilePhoto', {'name':file.name,'image':res});
                });

                // myModal.hide();
            }
        }
    }

    // inner dropdown in tables
    if ($('table').find('.selectpicker-inner').length) {
        $('.selectpicker-inner').selectpicker();

        $('.selectpicker-inner').on('hide.bs.select', function () {
            $('.fixed-table-body').css('overflow', '');
        });

        $('.selectpicker-inner').on('change', function () {
            $(".filter-option-inner-inner").text('Current Value '+$(this).val());
        });

        $('.selectpicker-inner + button').mouseover(function (e) {
            $('.fixed-table-body').css('overflow', 'inherit');
        });

        $('.selectpicker-inner + button').mouseleave(function (e) {
            $('.fixed-table-body').css('overflow', '');
        });
    }
});

function priceSorter(a, b) {
    var aa = a;
    var bb = b;
    if (aa.indexOf('$') + 1) {
        aa = a.replace('$', '');
        bb = b.replace('$', '');
    }
    return aa - bb;
}
window.livewire.on('hideFlashMsg',(data)=>{
    const el = document.getElementById("closeFlashMsg");
    if(el != 'undefined' && $(el).length){
        setTimeout(function() {
            el.click();
        }, 5000);
    }
});

/*$(document)
.off('changed.bs.select', '.sell-menu-select')
.on('changed.bs.select', '.sell-menu-select', function (e) {
    if ($(this).prop('tagName') == 'SELECT') {
        if ($(this).val() =='Cancel') {
            window.livewire.emit('openCancelSellCertificateModal',$(this).find(`option:selected`).attr('data-id'));
        } else if ($(this).val() == 'View') {
            var win = window.open($(this).find(`option:selected`).attr('data-href'), '_self');
            win.focus();
        }else if($(this).val() =='PriceAlert'){
            window.livewire.emit('openSetPriceAlertModal',$(this).find(`option:selected`).attr('data-id'));
        }
        $(this).selectpicker('val', '');
    }
});
*/
$('table[data-toggle="table"]').on('scroll-body.bs.table', function () {
    if ($(this).find($('td .dropdown-toggle'))) {
        $('td select + button.show').prev('select').selectpicker('toggle');
    }
});


// init popover
// var popoverTriggerList = [].slice.call(document.querySelectorAll('.link-notifications'));
// var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
//     return new bootstrap.Popover(popoverTriggerEl, {
//         template:
//             '<div class="popover" role="tooltip"><div class="popover-arrow"></div><h3 class="popover-header"></h3><div class="popover-body p-32"></div></div>',
//         customClass: 'notification-popover',
//         html: true,
//         sanitize: false,
//         content: function () {
//             return $('#notif-content').html();
//         },
//     });
// });
//
// if (window.innerWidth >= 1000) {
//     $('.link-notifications').on('show.bs.popover', function () {
//         $($('.container-fluid')[0]).addClass('popoverCSS');
//     });
//     $('.link-notifications').on('hide.bs.popover', function () {
//         $($('.container-fluid')[0]).removeClass('popoverCSS');
//     });
// }
document.addEventListener("livewire:load", function (event) {
    initMainDataTable();
    initSelectPicker();
    hideFilters();
    console.log("call plugins");
    Livewire.hook('message.processed', () => {
        console.log("recall all plugins");
        initMainDataTable();
        initSelectPicker();
        hideFilters();
    });
});

$(document).on('keypress','.specialChar',function (event){
    const key = event.key;
    const allowedChars = /[A-Za-z0-9\s]/;

    if (!allowedChars.test(key)) {
        event.preventDefault();
    }
})

$(document).on('keypress','.otp-input',function (event){
    var allowedKeys = [8,9,13,27,46];
    // Allow: , delete, tab, escape, enter and .
    if ( jQuery.inArray(event.keyCode,allowedKeys) !== -1
        || (event.keyCode == 65 && event.ctrlKey === true)
        || (event.keyCode >= 35 && event.keyCode <= 39)) {
        return;
    }
    else {
        if (    event.shiftKey
            || (event.keyCode < 48
                || event.keyCode > 57)
            && (
                event.keyCode < 96
                || event.keyCode > 105
            ) || event.target.value.length != 0 ) {
            event.preventDefault();
        }
    }
})
