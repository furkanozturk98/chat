<template>
  <div style="height: 700px;">
    <div v-for="item in items" @click="friendClick(item)">
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
    <!--    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/optimus-prime.jpeg" alt="">
      <div class="text">
        <h6>Optimus</h6>
        <p class="text-muted">
          Wanna grab a beer?
        </p>
      </div>
      <span class="time text-muted small">00:32</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover ">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/real-terminator.png" alt="">
      <div class="text">
        <h6>Skynet</h6>
        <p class="text-muted">
          Seen that canned piece of s?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/termy.jpg" alt="">
      <div class="text">
        <h6>Termy</h6>
        <p class="text-muted">
          Im studying spanish...
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rick.jpg" alt="">
      <div class="text">
        <h6>Richard</h6>
        <p class="text-muted">
          I'm not sure...
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>-->
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
            }
        }
    }
</script>

<style scoped>

</style>
