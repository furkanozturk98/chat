<template>
  <div class="messages overflow-auto" style="height: 700px;">
    <div v-for="item in items" :key="item.id" @click="groupClick(item)">
      <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
        <img class="profile-image" :src="'images/'+item.image" alt="">
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ item.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}" />
        </div>
        <span v-if="item.unread && selectedGroupId !== item.id" class="badge badge-success unread" style="padding: 7px">{{ item.unread }}</span>

        <span class="time text-muted small">13:21</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: 'ChatGroupList',
    props : ['nightMode'],

    data (){
        return {
            items : [],
            selectedGroupId: null,
            selectedItem: null
        }
    },

    mounted() {
        this.fetch();

        this.$eventHub.$on('groupMessageReceived',this.groupMessageReceived);

        this.$eventHub.$on('groupInviteApproved',this.groupInviteApproved);

    },

    methods: {
        async fetch(){
            const response = await this.$http.get('/api/get-groups');
            this.items = response.data.data;
        },

        async groupClick(item){
            this.$eventHub.$emit('groupClick',item);

            if(this.selectedItem === null){
                this.selectedItem = item;
            }

            if(this.selectedGroupId !== item.id &&  this.selectedItem.unread !== 0){
                this.selectedItem.unread = 0;
            }

            this.selectedItem = item;
            this.selectedGroupId = item.id;
        },

        groupMessageReceived(message){
            this.items.forEach(item => {
                if(item.friend.id === message.from){
                    item.unread += 1;
                }
            });
        },

        groupInviteApproved(group){
            this.items.push(group);
        }
    }
}
</script>

<style scoped>

</style>
