<template>
    <div>
        <router-link to="/tag" class="btn btn-secondary">Добавить</router-link>
        <hr>
        <table class="table">
            <tr v-for="(item, index) in items" :key="index">
                <td>{{ item.name }}</td>
                <td>
                    <button class="btn btn-danger btn-sm" @click="remove(item, index)"><i class="fa fa-trash"></i></button>
                    <router-link :to="`/tag/${item.id}`" class="btn btn-secondary btn-sm"><i class="fa fa-edit"></i></router-link>
                </td>
            </tr>
        </table>

    </div>
</template>

<script>


	export default {

		data(){
			return {
				items: [],
			}
		},

		created() {
			this.getItems();
		},

		methods: {

			getItems(){
				axios.get('/api/tags').then( res => { // not await for not block
					this.items = res.data;
				});
			},

			remove(item, index){
				if(!confirm('Удалить ' + item.name + '?')) return;
				axios.delete('/api/tag/' + item.id);
				this.items.splice(index, 1);
			}
		}
	}
</script>
