// 加载地区数据
const addressData = require('china-area-data/v3/data')

//引入lodash工具库
import _ from 'lodash'

//注册选择组件
Vue.component('select-district', {
    props: {
        //初始化城市数据
        initValue: {
            type: Array,
            default: () => ([])
        }
    },
    created () {
        this.setFromValue(this.initValue)
    },
    data() {
        return {
            provinces: addressData['86'], //初始化省列表
            cities: {},// 城市列表
            districts: {}, //地区列表
            provinceId: '',// 当前选中省
            cityId: '',// 当前选中市
            districtId: ''// 当前选中地区
        }
    },
    methods: {
        setFromValue (value) {
            //过滤空值
            value = _.filter(value)
            //如果value为空
            if(value.length === 0) {
                this.provinceId = ''
                return false
            }
            //从省列表匹配value，不存在则清空省
            const provinceId = _.findKey(this.provinces, o => o === value[0])
            if(!provinceId){
                this.provinceId = ''
                return false
            }

            this.provinceId = provinceId
            // 从当前城市列表找到与数组第二个元素同名的项的索引
            const cityId = _.findKey(addressData[provinceId], o => o === value[1]);
            // 没找到，清空城市的值
            if (!cityId) {
                this.cityId = '';
                return;
            }
            // 找到了，将当前城市设置成对应的ID
            this.cityId = cityId;
            // 由于观察器的作用，这个时候地区列表已经变成了对应城市的地区列表
            // 从当前地区列表找到与数组第三个元素同名的项的索引
            const districtId = _.findKey(addressData[cityId], o => o === value[2]);
            // 没找到，清空地区的值
            if (!districtId) {
                this.districtId = '';
                return;
            }
            // 找到了，将当前地区设置成对应的ID
            this.districtId = districtId;
        }
    },
    watch: {
        provinceId(newVal){
            if(!newVal){
                this.cities = {}
                this.cityId = ''
                return false
            }
            //根据选择的省，展示出对应的市区
            this.cities = addressData[newVal]

            // 若当前选中城市不再省下，清空已选择城市
            if(!this.cities[this.cityId]){
                this.cityId = ''
            }
        },
        cityId(newVal){
            if (!newVal) {
                this.districts = {}
                this.districtId = ''
                return false
            }
            // 将地区列表设为当前城市下的地区
            this.districts = addressData[newVal];
            // 如果当前选中的地区不在当前城市下，清空已选择地区
            if (!this.districts[this.districtId]) {
                this.districtId = '';
            }
        },
        // 当选择的区发生改变时触发
        districtId() {
            // 触发一个名为 change 的 Vue 事件，事件的值就是当前选中的省市区名称，格式为数组
            this.$emit('change', [this.provinces[this.provinceId], this.cities[this.cityId], this.districts[this.districtId]]);
        },
    }
});
