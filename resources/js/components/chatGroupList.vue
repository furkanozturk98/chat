<template>
  <div class="messages overflow-auto" style="height: 700px;">
    <div v-if="items.length > 0">
      <div v-for="item in items" :key="item.id" @click="groupClick(item)">
        <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
          <img class="profile-image" :src="'images/'+item.image" alt="">
          <div class="text" :class=" {'text-white' : nightMode}">
            <h6>{{ item.name }}</h6>
            <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}" />
          </div>
          <span v-if="item.unread && selectedGroupId !== item.id" class="badge badge-success unread" style="padding: 7px">{{ item.unread }}</span>

          <span class="time text-muted small">{{ item.last_message }}</span>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="alert alert-info mt-1" role="alert">
        You have no group
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: 'ChatGroupList',
    props : ['nightMode', 'items'],

    data (){
        return {
            selectedGroupId: null,
            selectedItem: null
        }
    },

    mounted() {
        this.$eventHub.$on('groupMessageReceived',this.groupMessageReceived);
    },

    methods: {
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
                if(item.id === message.group.id){
                    item.unread += 1;
                }
            });
        },

    }
}
</script>

<style scoped>

</style>
