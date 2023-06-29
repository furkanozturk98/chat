<template>
  <div class="messages overflow-auto">
    <div v-if="items.length > 0">
      <div v-for="item in items" :key="item.id" @click="friendClick(item)">
        <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
          <img class="profile-image" :src="'images/'+item.friend.image" alt="">
          <div class="text" :class=" {'text-white' : nightMode}">
            <h6>{{ item.friend.name }}</h6>
            <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
              {{ item.friend.about }}
            </p>
          </div>

          <span v-if="item.unread && selectedFriendId !== item.friend.id" class="badge badge-success unread" style="padding: 7px">{{ item.unread }}</span>

          <span class="time text-muted small">{{ item.last_message }}</span> <!-- last message send  -->
        </div>
      </div>
    </div>
    <div v-else>
      <div class="alert alert-info mt-1" role="alert">
        You have no friends
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: 'ChatFriendList',
        props : ['nightMode', 'items'],

        data (){
            return {
                selectedFriendId: null,
                selectedItem: null
            }
        },

        mounted() {
            this.$eventHub.$on('message-received',this.messageReceived);
        },

        methods: {
            async friendClick(item){
                this.$eventHub.$emit('friendClick',item);

                if(this.selectedItem === null){
                    this.selectedItem = item;
                }

                if(this.selectedFriendId !== item.friend.id &&  this.selectedItem.unread !== 0){
                    this.selectedItem.unread = 0;
                }

                this.selectedItem = item;
                this.selectedFriendId = item.friend.id;
            },

            messageReceived(message){
                this.items.forEach(item => {
                    if(item.friend.id === message.from){
                        item.unread += 1;
                    }
                });
            },

        }
    }
</script>

<style scoped>

</style>
