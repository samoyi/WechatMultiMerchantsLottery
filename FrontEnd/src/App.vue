<template>
  <div id="app">
      <div id="merchants" v-if="oMerchant">
          <h2>商户奖项</h2>
          <ol>
              <li v-for="(value, key) in oMerchant">
                  <h3>{{key}}</h3>
                  <table v-if="value.length">
                      <tr>
                          <th>奖品</th>
                          <th>中奖概率</th>
                          <th>奖品总数</th>
                          <th>奖品余量</th>
                      </tr>
                      <tr v-for="item in value" :key="value+'-'+item">
                          <td>{{item[0]}}</td>
                          <td>{{item[1]}}</td>
                          <td>{{item[2]}}</td>
                          <td>{{item[3]}}</td>
                      </tr>
                  </table>
              </li>
          </ol>
      </div>
      <div v-if="oMerchant">
          <h2>添加商户</h2>
          <p>
              <input id="newMerchantName" type="text" required placeholder="商户名" v-model.trim="newMerchantName" /><br />
              <input id="addMerchantBtn" type="button" value="添加" @click="addMerchantBtn" />
          </p>
      </div>
      <div v-if="oMerchant">
          <h2>添加奖品</h2>
          <p>
              <!-- <input id="merchantNameForAddPrize" type="text" required placeholder="商户名" v-model.trim="merchantNameForAddPrize"/><br /> -->
              <select v-model="merchantNameForAddPrize">
                  <option selected disabled value="default">选择商户</option>
                  <option v-for="(value, key) in oMerchant" :value="key">{{key}}</option>
              </select><br />
              <input id="prizeName" type="text" required placeholder="奖品名" v-model.trim="prizeName"/><br />
              <input id="percent" type="number" min="0" max="100" step="1" required placeholder="中奖概率" v-model="percent"/>%<br />
              <input id="total" type="number" min="1" step="1" required placeholder="奖品总数" v-model="total"/><br />
              <input id="addPrizeBtn" type="button" value="添加" @click="addMerchant" />
          </p>
      </div>
  </div>
</template>

<script>
export default {
    name: 'app',
    data () {
        return {
            // 商户数据
            oMerchant: null,

            // 添加商户
            newMerchantName: '',

            // 添加奖品
            prizeName: '',
            merchantNameForAddPrize: 'default',
            percent: '',
            total: '',
        }
    },
    mounted(){
        AJAX_GET('addData.php?act=get_prizes',
        res=>{
            let oMerchantData = {};

            let oPrizes = JSON.parse(res);

            AJAX_GET('addData.php?act=get_merchants',
                res=>{
                    let aMerchant = JSON.parse(res.trim());

                    aMerchant.forEach(item=>{
                        oMerchantData[item.merchantName] = [];
                        if(oPrizes[item.merchantID]){
                            oPrizes[item.merchantID].forEach(prize=>{
                                oMerchantData[item.merchantName].push([prize.prizeName, prize.probabilityPercent, prize.total, prize.remaining]);
                            });
                            oMerchantData[item.merchantName].reverse();
                        }
                    });
                    this.oMerchant = oMerchantData;
                },
                err=>{
                    alert(err.trim());
                    console.error(err.trim());
                })
        },
        err=>{
            alert(err.trim());
            console.error(err.trim());
        })
    },
    methods: {
        addMerchantBtn(){
            if(this.newMerchantName){
                if(this.newMerchantName in this.oMerchant){
                    alert('该商户已存在');
                    return;
                }

                let data = 'act=add_merchant&merchant=' + this.newMerchantName;
                AJAX_POST('addData.php', data,
                    res=>{
                        this.oMerchant[this.newMerchantName] = [];
                        this.newMerchantName = '';
                        alert(res.trim());
                    },
                    err=>{
                        alert(err.trim());
                        console.error(err.trim());
                    }
                );
            }
        },

        addMerchant(){

            if(this.prizeName && this.merchantNameForAddPrize && this.percent && this.total){

                if(this.oMerchant[this.merchantNameForAddPrize].map(item=>item[0]).includes(this.prizeName)){
                    alert('该商户已有该奖品');
                    return;
                }

                let data = 'act=add_prize&merchant=' + this.merchantNameForAddPrize
                + '&prize_name=' + this.prizeName
                + '&probability_percent=' + this.percent
                + '&total=' + this.total;
                AJAX_POST('addData.php', data,
                    res=>{

                        this.oMerchant[this.merchantNameForAddPrize].push([this.prizeName, this.percent, this.total, this.total]);

                        this.prizeName = '';
                        this.percent = '';
                        this.total = '';

                        alert(res.trim());
                    },
                    err=>{
                        alert(err.trim());
                        console.error(err.trim());
                    }
                );
            }
        },
    }
}



function AJAX_GET(sURL, fnSuccessCallback, fnFailCallback)
{
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', function()
    {
        if (xhr.readyState == 4)
        {
            if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
                // 必要的时候，使用 getResponseHeader() 检查首部信息
                fnSuccessCallback && fnSuccessCallback( xhr.responseText );
            }
            else{
                fnFailCallback && fnFailCallback( xhr.status );
            }
        }
    }, false);
    xhr.open("get", sURL, true);
    xhr.send(null);
}

function AJAX_POST(sURL, data, fnSuccessCallback, fnFailCallback)
{
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('readystatechange', function()
    {
        if (xhr.readyState == 4)
        {
            if ((xhr.status >= 200 && xhr.status < 300) || xhr.status == 304){
                // 必要的时候，使用 getResponseHeader() 检查首部信息
                fnSuccessCallback && fnSuccessCallback( xhr.responseText );
            }
            else{
                fnFailCallback && fnFailCallback( xhr.status );
            }
        }
    }, false);
    xhr.open("post", sURL, true);
    // 如果发送FormDate，则不需要设置Content-Type，但截至2017.5，FormDate的浏览器支持并不理想
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}
</script>

<style lang="scss">
#app>div{
    margin-bottom: 48px;
}
table, th, td {
    border: 1px solid black;
    text-align: center;
}
table{
    margin: 12px 0 28px 0;
}
</style>
