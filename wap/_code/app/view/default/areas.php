
<?$this->_extends("_layouts/wechat_layout");?>
<?$this->_block("contents");?>
<link rel="stylesheet" href="<?=$_BASE_DIR?>css/swiper.min.css">

    <!-- Demo styles -->
    <style>
    html, body {
        position: relative;
        height: 100%;
        background-color: #fff;
    }
    body {
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color:#000;
        margin: 0;
        padding: 0;
    }
    .swiper-container {
        width: 100%;
        height: 92%;
    }
    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;

        /* Center slide text vertically */
        display: -webkit-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        /* display: flex; */
        display: block;
        margin-top: 20px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        -webkit-justify-content: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        -webkit-align-items: center;
        align-items: center;
    }
    

    </style>
    <!-- Swiper -->
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="floor">
                    <div class="floor_title">
                        <table>
                            <tr><td class="floor_t01"><img src="<?=$_BASE_DIR?>images/book_03.jpg">&nbsp;&nbsp;中文报刊阅览室</td></tr>
                        </table>
                    </div>
                    <div class="floor_img">
                        <div>
                            <a href="<?=$_BASE_DIR?>images/floor_02.jpg" target="_blank"><img src="<?=$_BASE_DIR?>images/floor_02.jpg"></a>
                        </div>
                        <div class="floor_num">
                            二楼
                        </div>
                    </div>
                    <div class="floor_con">二楼中文报刊阅读室：各类中文报纸、现刊及近三年过刊合订本。</div>
                </div>
            </div>
            <div class="swiper-slide">  
                <div class="floor">
                    <div class="floor_title">
                        <table>
                            <tr><td class="floor_t02"><img src="<?=$_BASE_DIR?>images/book_07.jpg">&nbsp;&nbsp;社会科学图书借阅室</td></tr>
                        </table>
                    </div>
                    <div class="floor_img">
                        <div>
                            <a href="<?=$_BASE_DIR?>images/floor_03.jpg" target="_blank"><img src="<?=$_BASE_DIR?>images/floor_03.jpg"></a>
                        </div>
                        <div class="floor_num">
                            三楼
                        </div>
                    </div>
                    <div class="floor_con">    三楼社会科学图书借阅室：A类 马列主义、毛泽东思想、邓小平理论。B类 哲学、宗教。C类 社会科学总论。D类 政治、法律。E类 军事
F类 经济。G类 文化、科学、教育、体育。I类 文学。J类 艺术。K类 历史、地理。</div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="floor">
                    <div class="floor_title">
                        <table>
                            <tr><td class="floor_t04"><img src="<?=$_BASE_DIR?>images/book_04.jpg">&nbsp;&nbsp;外文借阅室</td></tr>
                            <tr><td class="floor_t03"><img src="<?=$_BASE_DIR?>images/book_05.jpg">&nbsp;&nbsp;自然科学图书借阅室1</td></tr>
                            
                            <tr><td class="floor_t05"><img src="<?=$_BASE_DIR?>images/book_06.jpg">&nbsp;&nbsp;自然科学图书借阅室2</td></tr>
                        </table>
                    </div>
                    <div class="floor_img">
                        <div>
                            <a href="<?=$_BASE_DIR?>images/floor_04.jpg" target="_blank"><img src="<?=$_BASE_DIR?>images/floor_04.jpg"></a>
                        </div>
                        <div class="floor_num">
                            四楼
                        </div>
                    </div>
                    <div class="floor_con">四楼有自然科学图书借阅室1、外文借阅室、自然科学图书借阅室2。</div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="floor">
                    <div class="floor_title">
                        <table>
                            <tr><td class="floor_t06"><img src="<?=$_BASE_DIR?>images/book_01.jpg">&nbsp;&nbsp;基本书库</td>
                            <td class="floor_t07"><img src="<?=$_BASE_DIR?>images/book_02.jpg">&nbsp;&nbsp;工具书阅览室</td></tr>
                            <tr><td class="floor_t08"><img src="<?=$_BASE_DIR?>images/book_08.jpg">&nbsp;&nbsp;特藏文献</td></tr>
                        </table>
                    </div>
                    <div class="floor_img">
                        <div>
                            <a href="<?=$_BASE_DIR?>images/floor_05.jpg" target="_blank"><img src="<?=$_BASE_DIR?>images/floor_05.jpg"></a>
                        </div>
                        <div class="floor_num">五楼</div>
                    </div>
                    <div class="floor_con">五楼有基本书库、工具书阅览室、特藏书库。</div>
                </div>
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
    </div>

    <!-- Swiper JS -->
    <script src="<?=$_BASE_DIR?>js/swiper.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true
    });
    </script>
<?php $this->_endblock(); ?>