<template>
    <div class="row">
        <div class="col-4">
            <img :src="`/images/${item.image}`" class="img-thumbnail">
        </div>
        <div class="col-8">
            <h4>{{item.name}}</h4>
            <div>{{item.authors}}</div>
            <div>{{item.year}}</div>
            <span class="badge badge-secondary ml-1" v-for="tag in item.tags">{{ tag.name }}</span>
        </div>
    </div>
</template>

<script>
    export default {
    	data(){
    		return {
    			item: {}
            }
        },

        async mounted() {
    		let res = await axios.get('/api/book/' + this.$route.params.id);
			this.item = res.data;
		}
	}
</script>