<template>
  <div style="height: 700px;">
    <div v-for="item in items" :key="item" @click="friendClick(item)">
      <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
        <img class="profile-image" :src="'images/'+item.friend.image" alt="">
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ item.friend.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ item.friend.about }}
          </p>
        </div>

        <span v-if="item.unread && selectedFriendId !== item.friend.id" class="badge badge-success unread" style="padding: 7px">{{ item.unread }}</span>

        <span class="time text-muted small">13:21</span> <!-- last message send  -->
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: 'ChatFriendList',
        props : ['nightMode'],

        data (){
            return {
                items : [],
                selectedFriendId: null,
                selectedItem: null
            }
        },

        mounted() {
            this.fetch();

            this.$eventHub.$on('messageReceived',this.messageReceived);

            this.$eventHub.$on('friendRequestApproved',this.friendRequestApproved);
        },

        methods: {
            async fetch(){
                const response = await this.$http.get('/api/friend-list');
                this.items = response.data.data;
            },

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

            friendRequestApproved(friend){
                this.items.push(friend);
            }
        }
    }
</script>

<style scoped>

</style>
