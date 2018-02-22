<?php

if ( ! function_exists( 'curl_post' ) ) {
	/**
	 * @param $url
	 * @param array $data
	 * @return mixed
	 */
	function curl_post( $url, array $data = [] )
	{
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );

		$response = curl_exec( $ch );

        $status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        curl_close( $ch );

        return compact('status', 'response');
	}
}


if ( ! function_exists( 'curl_post_json' ) ) {
    /**
     * @param $url
     * @param array $data
     * @return mixed
     */
    function curl_post_json( $url, array $data = [] )
    {
        $json = json_encode( $data );

        $ch = curl_init( $url );

        $options = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $json,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen( $json )
            ]
        ];

        curl_setopt_array( $ch, $options );

        $response = curl_exec( $ch );

        $status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        curl_close( $ch );

        return compact('status', 'response');
    }
}

if ( ! function_exists( 'curl_delete' ) ) {
    /**
     * @param $url
     * @param array $headers
     * @return mixed
     */
    function curl_delete( $url, array $headers = [] )
    {
        $ch = curl_init( $url );

        $options = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CUSTOMREQUEST   => "DELETE",
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_TIMEOUT         => 10,
        ];

        curl_setopt_array( $ch, $options );

        $response = curl_exec( $ch );

        $status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        curl_close( $ch );

        return compact('status', 'response');
    }
}

if ( ! function_exists( 'curl_download' ) ) {
    /**
     * @param $source string The file you want to download
     * @param $directory string The location you want to save the file to
     * @param $filename string|null The filename of the downloaded file
     * @return string
     * @throws Exception
     */
    function curl_download( $source, $directory, $filename = null )
    {
        $directory = rtrim( $directory, DIRECTORY_SEPARATOR );

        if ( ! is_dir( $directory ) ) {
            throw new \Exception( "The target directory does not exist: '{$directory}'" );
        }

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $source );

        $path = ! is_null( $filename ) ? $directory . DIRECTORY_SEPARATOR . $filename : tempnam( $directory, uniqid() );

        $file = @fopen( $path , 'w+' );

        if ( ! is_resource( $file ) ) {
            throw new \Exception( "Could not open file path for writing: '{$path}'" );
        }

        curl_setopt( $ch, CURLOPT_FILE, $file );

        curl_exec( $ch );
        curl_close( $ch );
        fclose( $file );

        return $path;
    }
}
