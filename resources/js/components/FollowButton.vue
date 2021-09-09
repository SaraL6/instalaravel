<template>
    <div>
        <button
            class="btn btn-primary ml-4"
            @click="followUser"
            v-text="buttonText"
        ></button>
    </div>
</template>

<script>
export default {
    props: ["userId", "follows", "followers"],
    mounted() {
        // console.log(this.follows);
        // console.log(this.followers);
    },

    data: function() {
        return {
            status: this.follows,
            follower: this.followers
        };
    },

    methods: {
        followUser() {
            axios
                .post("/follow/" + this.userId)
                .then(response => {
                    this.status = !this.status;
                    if (this.status == true) {
                        this.follower++;
                    } else {
                        this.follower--;
                    }
                    // console.log(this.follower);

                    this.$root.$emit(
                        "follow_event",
                        this.follower,
                        this.status
                    );
                    //  console.log("followUser" + this.follower, this.status);
                    // console.log(this.follower);
                })
                .catch(errors => {
                    if (errors.response.status == 401) {
                        window.location = "/login";
                    }
                });

            // console.log(this.followersCount);
        }
    },
    computed: {
        buttonText() {
            return this.status ? "Unfollow" : "Follow";
        }
    }
};
</script>
