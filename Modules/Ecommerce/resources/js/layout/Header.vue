<template>
  <header class="main-header main-header-template-01">
    <div class="main-header-height" style="min-height: 112px;">
      <div class="main-header main-header-template-01" style="background-color: darkblue">
        <div class="top-bar top-bar-template-header-01" style="background-color: darkblue">
          <div class="container-fluid">
            <div class="wraper-top-bar">
              <p>Miễn phí vận chuyển với đơn hàng trên 1.500.000 đ</p>
            </div>
          </div>
        </div>

        <!-- Top right menu -->

        <ul class="top-menu" style="display: flex; padding: 0 15px; margin-left: auto">
          <li><a href="/vn/en-vn"><span style="color: white;">English</span></a></li>
          <li><a href="//www.asics.com/vn/vi-vn/contact"><span style="color: white; padding: 0 15px;">Trợ Giúp</span></a></li>
          <li><a href="//www.asics.com/vn/vi-vn/store-locator"><span style="color: white; padding: 0 15px;">Vị Trí Cửa Hàng</span></a></li>
          <li><a href="/vn/vi-vn/account-login"><span style="color: white; padding: 0 15px;">Tài khoản của tôi</span></a></li>
        </ul>
      </div>
    </div>

    <div class="header-middle">
      <div class="container-fluid">
        <div class="flexContainer flexAlignCenter rowFlexMargin">

          <!-- Hamburger -->
          <div class="header-menu site-menu-mobile">
            <div id="site-menu-handle" class="site-menu hamburger-menu">
              <span class="bar"></span>
            </div>
          </div>

          <!-- Logo -->
          <div class="header-logo header-mid">
            <div class="wrap-logo">
              <h1><a href="/">ASICS</a></h1>
            </div>
          </div>

          <!-- Desktop Menu -->
          <div class="header-menu site-menu-desktop">
            <nav class="main-nav text-center">
              <ul class="level0 clearfix">
                <li class="nav1"><a href="/">Trang chủ</a></li>
                <li class="nav1 has-sub level0">
                  <a href="/products/category/all">Sản phẩm <i class="fa fa-chevron-down"></i></a>
                  <ul class="sub-menu level1">
                    <li class="nav2" v-for="cat in categories" :key="cat.id">
                      <a :href="`/products/category/${cat.id}`">{{ cat.name }}<i class="fa fa-chevron-down"></i></a>
                      <ul class="sub-menu level2">
                        <li v-for="sub in cat.subcategories" :key="sub.id">
                          <a :href="`/products/category/${sub.id}`">{{ sub.name }}</a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
                <li class="nav1"><a href="/blogs/news">Tin tức</a></li>
                <li class="nav1"><a href="/contact">Liên hệ</a></li>
              </ul>
            </nav>
          </div>

          <!-- Search Icon -->
          <div class="search header--icon">
            <a href="javascript:;" class="link-box header-site-nav" @click="toggleSearch">
              <span class="icon-box"><i class="fa-solid fa-magnifying-glass fa-xl"></i></span>
            </a>

            <div v-if="showSearch" class="wpo-wrapper-search wpo-wrapper-search-dk site--nav">
              <span class="box-triangle">
                <svg viewBox="0 0 20 9">
                  <path d="M.47 9c.27-.27.57-.57.9-.9C3.12 6.36 5.75 3.76 9.25.31c.41-.41 1.08-.41 1.5 0L19.49 9H.47z" fill="#fff"/>
                </svg>
              </span>
              <div class="site-nav-container">
                <p class="titlebox">Tìm kiếm</p>
                <div class="search-product-nav">
                  <form @submit.prevent="onSearch">
                    <input v-model="searchKey" class="search-product-input" type="text" placeholder="Tìm kiếm sản phẩm..." />
                    <button type="submit" class="btn-search-product">
                      <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Account -->
          <div class="account header--icon">
            <a href="/vn/vi-vn/account-login" class="link-box header-site-nav">
              <span class="icon-box"><i class="fa-regular fa-user fa-xl"></i></span>
            </a>
          </div>

          <!-- Cart -->
          <div class="cart header--icon">
            <a href="/cart" class="link-box">
              <span class="icon-box">
                <i class="fa-solid fa-cart-plus fa-xl"></i>
                <span class="count-holder">
                  <span class="count">{{ cartCount }}</span>
                </span>
              </span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',
  data() {
    return {
      showSearch: false,
      searchKey: '',
      cartCount: 0,
      categories: [
        {
          id: 1,
          name: 'Giày Nam',
          subcategories: [
            { id: 11, name: 'Chạy bộ' },
            { id: 12, name: 'Thể thao' }
          ]
        },
        {
          id: 2,
          name: 'Giày Nữ',
          subcategories: [
            { id: 21, name: 'Chạy bộ' },
            { id: 22, name: 'Thời trang' }
          ]
        }
      ]
    }
  },
  methods: {
    toggleSearch() {
      this.showSearch = !this.showSearch;
    },
    onSearch() {
      window.location.href = `/products/search-name?key=${this.searchKey}`;
    }
  }
}
</script>

<style scoped>
.count {
  padding: 0 6px;
  background-color: darkblue;
  border-radius: 15px;
  position: relative;
  top: -37px;
  right: -14px;
  color: white;
}
</style>
