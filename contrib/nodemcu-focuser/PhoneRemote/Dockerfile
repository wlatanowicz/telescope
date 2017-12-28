FROM ubuntu:16.04

RUN apt-get update \
	&& apt-get install -y curl git

RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - \
	&& apt-get install -y nodejs \
	&& npm -g install gulp yarn cordova


WORKDIR /PhoneRemote

COPY package.json package.json
COPY yarn.lock yarn.lock

RUN yarn install

COPY . /PhoneRemote

VOLUME /platforms

RUN gulp build
RUN cordova platform add ios
RUN cordova prepare ios

CMD rm -Rf /platforms/ios && cp -R /PhoneRemote/platforms/ios /platforms/ios
