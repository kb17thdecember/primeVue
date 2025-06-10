<template>
  <main class="mainContainer-theme">
    <!-- Home slider -->
<!--    <div id="home-slider">-->
<!--      <div id="homepage-slider" class="fade">-->
<!--        <div>-->
<!--          <div class="item">-->
<!--            <div class="slide-img">-->
<!--              <a href="#">-->
<!--                <picture>-->
<!--                  <img alt="Slide 1" />-->
<!--                </picture>-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div>-->
<!--          <div class="item">-->
<!--            <div class="slide-img">-->
<!--              <a href="#">-->
<!--                <picture>-->
<!--                  <img alt="Banner" />-->
<!--                </picture>-->
<!--              </a>-->
<!--            </div>-->
<!--            <div class="content-slide">-->
<!--              <div class="container-fluid">-->
<!--                <div class="slide-btn-content"></div>-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--        <div>-->
<!--          <div class="item">-->
<!--            <div class="slide-img">-->
<!--              <a href="#">-->
<!--                <picture>-->
<!--                  <img alt="Slide 3" />-->
<!--                </picture>-->
<!--              </a>-->
<!--            </div>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

    <!-- Banner home -->

    <section id="section-collection-info" class="section section-collection-info mt-5">
      <div class="container-fluid">
        <div class="rowFlexMargin">
          <div class="col-md-6">
            <div class="collectionItem">
              <div class="collectionImage fade-box effectFour">
                <a href="#" class="lazyloaded">
                  <img class="img-responsive no-border mediabox-img" alt="50%" />
                </a>
                <div class="collectionGroup">
                  <div class="box-button">
                    <a href="#" class="button-bn">Về chúng tôi</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 position-relative">
            <br /><br /><br /><br /><br /><br /><br /><br />
            <h1>Giày thể thao ASICS SPORTSTYLE EX89...</h1>
            <div class="button-content-box">
              <a href="#">Xem ngay</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Sản phẩm mới -->
    <section id="section-collection-home" class="section section-collection">
      <div class="wrapper-heading-home text-center">
        <div class="container-fluid">
          <div class="groupTitle-home">
            <h2><a href="#">Sản phẩm mới</a></h2>
            <p>Cập nhật những sản phẩm mới nhất</p>
          </div>
        </div>
      </div>
      <div class="wrapper-collection-1">
        <div class="container-fluid">
          <div class="row">
            <div class="content-product-list clearfix">
              <!-- Example product loop -->
              <div class="col-md-3 col-sm-4 col-xs-6 pro-loop col-20" v-for="product in products">
                <div class="product-block product-resize fixheight">
                  <div class="product-img">
                    <a href="" class="image-resize fade-box" style="height: 200px;">
                      <picture>
                        <img :src="product.image[0]" class="img-loop mediabox-img" alt="Product" />
                      </picture>
                    </a>
                  </div>
                  <div class="product-detail clearfix">
                    <div class="box-pro-detail">
                      <h3 class="pro-name"><a href="#">{{ product.name}}</a></h3>
                      <div class="box-pro-prices">
                        <p class="pro-price highlight">
                          <span>{{ product.price - (product.price * product.discount) / 100 }}₫</span>
                          <span class="pro-price-del">
                            <del class="compare-price">{{product.price}}₫</del>
                          </span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End product loop -->
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Blog -->
    <section id="section-blog" class="section section-blog">
      <div class="wrapper-heading-home text-center">
        <div class="container-fluid">
          <div class="groupTitle-home">
            <h2><a href="#">Tin tức</a></h2>
            <p>Cập nhật tin tức mới nhất!</p>
          </div>
        </div>
      </div>
      <div class="wrapper-content-home">
        <div class="container-fluid">
          <div class="row" id="blog-slide">
            <div class="col-md-4 item-blog no-pd" v-for="n in 3" :key="n">
              <div class="velaBlogItem">
                <div class="blogPostImage">
                  <a href="#" class="fade-box">
                    <picture>
                      <img class="img-responsive mediabox-img" alt="Blog post" />
                    </picture>
                  </a>
                </div>
                <div class="blogPostContent">
                  <h3 class="blogPostTitle">
                    <a href="#" style="width: 360px">Tiêu đề tin tức</a>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import {usePage} from "@inertiajs/vue3";

const {props} = usePage();

const products = props.products

console.log(props.products)
</script>

<style scoped>
</style>