import 'bootstrap'
import Vue from 'vue'
import axios from 'axios'
import './functions'


import Slick from 'vue-slick'
import VueInstagram from 'vue-instagram'
import InfiniteLoading from 'vue-infinite-loading'

// original component
import list from './list.vue'
import ranking from './ranking.vue'
import ranking2 from './ranking2.vue'
import staff from './staff.vue'
import loader from './loader.vue'
import posts from './posts.vue'
import banner from './banner.vue'


import Poster from './poster.vue'
import Casts from './casts.vue'
import Lightbox from 'vue-pure-lightbox'

Vue.use(Lightbox)



/*******************
  load
*******************/

window.onload = () =>{
  document.getElementById('preLoader').classList.add('preloader__remove')
}

/*******************
  square
*******************/

let square = () => {
  const square = document.querySelectorAll('.square')
  square.forEach((v,i)=>{
    v.style.height = v.clientWidth+'px'
  })
}


/*******************
  picture
*******************/

// ボックスの横幅から、黄金比での縦サイズを求めて四捨五入
let pictureHorizon = () => {
  const picture = document.querySelectorAll('.picture-h')
  picture.forEach((v,i)=>{
    v.style.height = Math.round(v.clientWidth / 1.618)+'px'
  })
}
let pictureVertical = () => {
  const picture = document.querySelectorAll('.picture-v')
  picture.forEach((v,i)=>{
    v.style.height = Math.round(v.clientWidth * 1.618)+'px'
  })
}
let poster = () => {
  const picture = document.querySelectorAll('.poster')
  picture.forEach((v,i)=>{
    v.style.height = Math.round(v.clientWidth * 1.415)+'px'
  })
}
let pictureCunstom = (el,type='height',num=2.2) => {
  const picture = document.querySelectorAll(el)
  picture.forEach((v,i)=>{
    if(type=='height'){
      v.style.height = Math.round(v.clientWidth / num)+'px'
    }else if(type=='min-height'){
      v.style.minHeight = Math.round(v.clientWidth / num)+'px'
    }
  })
}
pictureHorizon()

/*******************
  query
*******************/
let query = ''
// カテゴリー用
if(typeof cat_id !== 'undefined') query += '&categories='+cat_id
// タグ用
if(typeof tag_id !== 'undefined') query += '&tags='+tag_id
// 検索結果用
if(typeof search !== 'undefined') query += '&search='+search

/*******************
  Fixed header
*******************/

window.onscroll = () => {
  const el = document.querySelector('.header__inner')
  const readyClass = 'header__state_ready'
  const fixedClass = 'header__state_fixed'
  if(document.documentElement.scrollTop > 110 || document.body.scrollTop > 110){
    el.classList.add(readyClass)
  }else{
    el.classList.remove(readyClass)
  }
  if(document.documentElement.scrollTop > 111 || document.body.scrollTop > 111){
    el.classList.add(fixedClass)
  }else{
    el.classList.remove(fixedClass)
  }
}


/*******************
  Nav
*******************/

const nav_el = document.querySelector('.header__nav_icon')
const nav_state_class = 'header__nav_state_on'
const nav_target_class = '.header__nav'
const nav_close_class = '.header__nav_close'

nav_el.addEventListener('click',()=>{
  if(document.querySelector('.'+ nav_state_class) == null){
    document.querySelector(nav_target_class).classList.add(nav_state_class)
  }else{
    document.querySelector(nav_target_class).classList.remove(nav_state_class)
  }
},false)

document.querySelector(nav_close_class).addEventListener('click',()=>{
  document.querySelector(nav_target_class).classList.remove(nav_state_class)
},false)



/*******************
  Instagram
*******************/

if (document.getElementById('Instagram')) {
  const instagramInstance = new Vue({
    el: "#Instagram",
    components: {
      VueInstagram
    },
  })
}



/*******************
  posts
*******************/

if (document.getElementById('Posts')) {
  const postsInstance = new Vue({
    el: "#Posts",
    data() {
      return {
        posts: [],
      }
    },
    components: {
      list
    },
    mounted(){
      axios(BASEURL+'posts')
      .then( (res) =>{
        this.posts = res.data
      })
    },
  })
}

if (document.getElementById('archives')) {
  const archivesInstance = new Vue({
    el: "#archives",
    data() {
      return {
        archives: [],
        page:1,
        total:0,
        secondflag: true,
      }
    },
    components: {
      list,
      posts,
      InfiniteLoading
    },
    methods:{
      infiniteHandler($state) {
        setTimeout(() => {
          this.page = this.page + 1
          axios.get(BASEURL + '/posts?_embed&page='+this.page+query)
          .then( (response) => {
            response.data.map((x)=>{
              this.archives.push(x)
            })
          })
          $state.loaded()
          if(this.page >= this.total) $state.complete()
        }, 1000); 
      },
    },
    mounted(){
      axios(BASEURL+'posts?_embed&page=1'+query)
      .then( (res) =>{
        this.archives = res.data
        if(!res.data.length) secondflag = false
      })
    },
  })
}




/*******************
  slider
*******************/

if (document.getElementById('Slider')) {
  const sliderInstance = new Vue({
    el: "#Slider",
    components: {
      Slick
    },
    data() {
      return {
        slickOptions: {
          slidesToShow: 1,
          centerMode: true,
          centerPadding: '0px',
          autoplay: true,
          fade: true,
		    },
      }
    },
    mounted(){
    },
    methods: {
      next() {
        this.$refs.slick.next()
      },
      prev() {
        this.$refs.slick.prev()
      },
      reInit() {
        // Helpful if you have to deal with v-for to update dynamic lists
        this.$nextTick(() => {
          this.$refs.slick.reSlick()
        })
      },
    },
  })
}


/*******************
  map
*******************/


if (document.getElementById('Map')) {
  const mapInstance = new Vue({
    el: "#Map",
    data() {
      return {
        lat: 34.6704542,
        lng: 135.5013464,
        zoom: 16,
        icon: THEME_URL + "/assets/mappin.png",
        geometry:{
          hue:'#111',       // 色
          gamma:0.1,        // ガンマ 0.01 ~ 10
          lightness:-70,    // 明度  -100 ~ 100
          saturation:-100,   // 彩度 -100 ~ 100
        },
        labels:{
          hue:'#ae9e74',       // 色
          gamma:1,        // ガンマ
          lightness:0,    // 明度
          saturation: -50, // 彩度
        }
      }
    },
    mounted(){
      let map
      let marker
      let center = {
        lat: Number(this.lat), // 緯度
        lng: Number(this.lng) // 経度
      }
      map = new google.maps.Map(document.getElementById('Map'), { // #sampleに地図を埋め込む
        center: center, // 地図の中心を指定
        zoom: this.zoom, // 地図のズームを指定
        disableDefaultUI: true,
        styles: [
          {
            featureType: 'all',
            elementType: 'geometry',
            stylers: [
              { hue: this.geometry.hue },
              { gamma: this.geometry.gamma },
              { lightness: this.geometry.lightness },
              { saturation: this.geometry.saturation },
            ],
          },
          {
            featureType: 'all',
            elementType: 'labels',
            stylers: [
              { hue: this.labels.hue },
              { gamma: this.labels.gamma },
              { lightness: this.labels.lightness },
              { saturation: this.labels.saturation },
            ],
          },
        ]
      })
      marker = new google.maps.Marker({ // マーカーの追加
        position: center, // マーカーを立てる位置を指定
        map: map, // マーカーを立てる地図を指定
        icon: this.icon // マーカーのアイコン指定
      })
    },
  })
}


/*******************
  staff
*******************/

if (document.getElementById('staff')) {
  const staffInstance = new Vue({
    el: "#staff",
    components: {
      staff
    },
    data() {
      return {
        posts: [],
        // secondflag: true,
      };
    },
    mounted(){
      axios.get(BASEURL + 'users?_embed&per_page=100')
      .then( (response) => {
        // if( response.data.length == 0 ) this.secondflag = false

        this.posts = response.data
        this.posts.sort((a,b)=>{
          if(a.acf.castlist<b.acf.castlist) return -1;
          if(a.acf.castlist > b.acf.castlist) return 1;
          return 0;
        })
        this.$nextTick(()=>{
          // this.posts = this.posts.filter(this.catFilter)
          this.posts = this.posts.filter((el,i,a)=>{
            return el.acf.castlist != undefined
          })
        })
        //console.log(response.data)
          //console.log(response)
      })
    }
  })
}


/*******************
  ranking
*******************/

if (document.getElementById('ranking')) {
  const rankingInstance = new Vue({
    el: "#ranking",
    components: {
      ranking
    },
    data() {
      return {
        ranking: [],
        users: [],
        user: []
      };
    },
    methods: { 
      getUser(userId){
        const promise = new Promise((resolve, reject) => {
          axios.get(BASEURL + 'authors/'+userId)
          .then( (res) => {
            this.$nextTick(()=>{
              resolve(res.data[0])
            })
          })
        })
        return promise
      },
    },
    mounted(){
      axios.get(BASEURL + 'ranking')
      .then((res)=>{
        (async () => {
          for (let x of res.data[0].acf.ranks) {
            const result = await this.getUser(x.user.ID)
            this.ranking.push(result)
            this.$nextTick(()=>{
              pictureCunstom('.rank__image_first','min-height',2.2)
              pictureCunstom('.rank__image_other','min-height',2)
            })
          }
        })()
      })
    }
  })
}


/*******************
  Single
*******************/

if (document.getElementById('singlePage')) {
  const singlePageInstance = new Vue({
    el: "#singlePage",
    components: {
      loader
    },
    data() {
      return {
        post: [],
      };
    },
    methods: {
      getImgUrl(data,imagesize){
        const imageSize = imagesize
        if(data._embedded && 
           data._embedded['wp:featuredmedia'] &&
           data._embedded['wp:featuredmedia'][0].media_details &&
           data._embedded['wp:featuredmedia'][0].media_details.sizes[imageSize]){
          return data._embedded['wp:featuredmedia'][0].media_details.sizes[imagesize].source_url
        }else{
          return false
        }
      },
      postDate(flag) {
        const dateArray = this.post.date.split('-');
        const y = dateArray[0];
        const m = dateArray[1];
        const d = dateArray[2].split("T")[0];
        return flag ? y+m+d : y + "/" + m + "/" + d;
      }
    },
    mounted(){
      axios.get(BASEURL + 'posts/'+post_id+'?_embed')
      .then( (res) => {
        this.post = res.data
      })
    }
  })
}



if (document.getElementById('lightbox')) {
  const lightboxInstance = new Vue({}).$mount('#lightbox')
}




/*******************
  Poster
*******************/

if (document.getElementById('Poster')) {
  const PosterInstance = new Vue({
    el: "#Poster",
    data() {
      return {
        datas: [],
      };
    },
    components:{
      Poster
    },
    methods: {
    },
    mounted(){
      axios.get(BASEURL + 'gallery?per_page=4&_embed')
      .then( (res) => {
        this.datas = res.data
        this.$nextTick(()=>{
          poster()
        })
      })
    }
  })
}


/*******************
  banner
*******************/

if (document.getElementById('Banner')) {
  const BannerInstance = new Vue({
    el: "#Banner",
    data() {
      return {
        datas: [],
      };
    },
    components:{
      banner
    },
    methods: {
    },
    mounted(){
      axios.get(BASEURL + 'pages?slug=home')
      .then( (res) => {
        this.datas = res.data[0].acf.banner
        this.$nextTick(()=>{
          pictureHorizon()
        })
      })
    }
  })
}



/*******************
  casts
*******************/

if (document.getElementById('Casts')) {
  const CastsInstance = new Vue({
    el: "#Casts",
    data() {
      return {
        datas: [],
      }
    },
    components:{
      Casts
    },
    methods: {
    },
    mounted(){
      axios.get(BASEURL + 'authors?segment=author')
      .then( (res) => {
        this.datas = res.data
        this.$nextTick(()=>{
          pictureCunstom('.casts__figure','min-height',0.832 )
        })
      })
    }
  })
}

/*******************
  Cast
*******************/

if (document.getElementById('Cast')) {
  const CastInstance = new Vue({
    el: "#Cast",
    data() {
      return {
        data: '',
      }
    },
    components:{
      // Lightbox
    },
    methods: {
    },
    mounted(){
      axios.get(BASEURL + 'authors/'+authorId)
      .then( (res) => {
        this.data = res.data[0]
        this.$nextTick(()=>{
          // pictureCunstom('.casts__figure','min-height',0.832 )
        })
      })
    }
  })
}


/*******************
  固定ページ上
*******************/
if (document.getElementById('Gallery')) {
  const GalleryInstance = new Vue({
    el: "#Gallery",
    data() {
      return {
        data: [],
      }
    },
    components:{
      //Lightbox
    },
    methods: {
    },
    mounted(){
      //console.log(BASEURL + 'pages/' + page_id)
      axios.get(BASEURL + 'pages/' + page_id)
      .then( (res) => {
        //console.log(res.data)
        //this.datas = res.data[0]
        this.data = res.data
        this.$nextTick(()=>{
          // pictureCunstom('.casts__figure','min-height',0.832 )
        })
      })
    }
  })
}
