from django.db import models
from django.contrib.auth.models import User

class UserProfile(models.Model):
    #Note: this is required to link UserProfile to a User model instance
    user = models.OneToOneField(User)

    #to allow profile pictures
    picture = models.ImageField(upload_to='profile_images',blank=True)

    # Override unicode method
    def __unicode__(self):
        return self.user.username

