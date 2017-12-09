<template>
  <div id="app">
      <h1>首页</h1>
  </div>
</template>

<script>
export default {
    data () {
        return {

        }
    },
}



// function AJAX_GET(sURL, fnSuccessCallback, fnFailCallback)
// {
//     let xhr = new XMLHttpRequest();
//     xhr.addEventListener('readystatechange', function()
//     {
//         if (xhr.readyState == 4)
//         {
//             if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
//                 // 必要的时候，使用 getResponseHeader() 检查首部信息
//                 fnSuccessCallback && fnSuccessCallback( xhr.responseText );
//             }
//             else{
//                 fnFailCallback && fnFailCallback( xhr.status );
//             }
//         }
//     }, false);
//     xhr.open("get", sURL, true);
//     xhr.send(null);
// }
//
// function AJAX_POST(sURL, data, fnSuccessCallback, fnFailCallback)
// {
//     let xhr = new XMLHttpRequest();
//     xhr.addEventListener('readystatechange', function()
//     {
//         if (xhr.readyState == 4)
//         {
//             if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
//                 // 必要的时候，使用 getResponseHeader() 检查首部信息
//                 fnSuccessCallback && fnSuccessCallback( xhr.responseText );
//             }
//             else{
//                 fnFailCallback && fnFailCallback( xhr.status );
//             }
//         }
//     }, false);
//     xhr.open("post", sURL, true);
//     // 如果发送FormDate，则不需要设置Content-Type，但截至2017.5，FormDate的浏览器支持并不理想
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.send(data);
// }
</script>

<style lang="scss">

</style>
