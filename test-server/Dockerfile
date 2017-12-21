FROM maltyxx/sshd

RUN echo "deb http://deb.debian.org/debian stretch main" > /etc/apt/sources.list.d/stretch.list && \
    echo "Package: *\\nPin: release n=jessie\\nPin-Priority: 900\\n\\nPackage: libpcre3*\\nPin: release n=stretch\\nPin-Priority: 1000" > /etc/apt/preferences && \
    apt-get update && apt-get install -y \
    imagemagick \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng12-dev \
    libicu-dev \
    libxml2-dev \
    vim \
    wget \
    unzip \
    git \
    g++ \
    gcc \
    make

# Install dependencies + gm sources
RUN wget http://ftp.icm.edu.pl/pub/unix/graphics/GraphicsMagick/1.3/GraphicsMagick-1.3.25.tar.gz

# Unzip + compile
RUN tar zxvf GraphicsMagick-1.3.25.tar.gz
RUN cd GraphicsMagick-1.3.25 && ./configure && make && make install

# Cleanup
RUN rm -rf GraphicsMagick-*
